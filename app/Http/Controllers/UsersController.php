<?php

namespace WA\Http\Controllers;

use Cartalyst\DataGrid\Laravel\Facades\DataGrid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Response;
use View;

use WA\Helpers\Traits\SetLimits;

use WA\DataStore\User\User;
use WA\DataStore\User\UserTransformer;
use WA\Repositories\User\UserInterface;

use WA\DataStore\Package\PackageTransformer;
use WA\Repositories\Package\PackageInterface;

use WA\DataStore\Allocation\Allocation;
use WA\DataStore\Content\Content;
use WA\DataStore\Asset\Asset;

use DB;
use Cache;
use Log;

/**
 * Users resource.
 *
 * @Resource("Users", uri="/users")
 */
class UsersController extends FilteredApiController
{
    use SetLimits;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * UsersController constructor.
     *
     * @param UserInterface $user
     * @param Request $request
     */
    public function __construct(
        UserInterface $user,
        PackageInterface $package,
        Request $request
    ) {
        parent::__construct($user, $request);
        $this->packageInterface = $package;
        $this->userInterface = $user;
    }

    /*
     *  This functions returns the packages with conditions accomplished by the user.
     *
     *  @userId : The id of the User.
     *  @return: Array of Packages.
     */
    public function usersPackages($userId, Request $request)
    {
        $user = $this->userInterface->byId($userId, 1);
        $companyId = $user->companyId; // number
        $address = $user->addresses; // Array
        $udlValues = $this->getUdlValuesFromUser($user); // Array

        $packages = $this->packageInterface->getAllPackageByCompanyId($user->companyId);
        $packagesOk = [];

        foreach ($packages as $pack) {
            $isOk = true;
            $isOk = $isOk && $this->checkUserAttributes($user, $pack->conditions);
            $isOk = $isOk && $this->checkIfHasAnyAddress($address, $pack->conditions);
            foreach ($udlValues as $udl) {
                $isOk = $isOk && $this->checkIfHasAnyUdl($udl, $pack->conditions);
            }

            if ($isOk) {
                array_push($packagesOk, $pack);
            }
        }

        if(count($packagesOk) > 0) {
            return $this->transformToJson($packagesOk);    
        }

        $error['message'] = 'The user doesn\'t fulfill any packages conditions';
        return response()->json($error)->setStatusCode($this->status_codes['ok']);        
    }

    public function getLoggedInUser(Request $request)
    {
        $criteria = $this->getRequestCriteria();
        $this->userInterface->setCriteria($criteria);
        $user = Auth::user();

        if ($user === null) {
            $error['errors']['get'] = Lang::get('messages.NotExistClass', ['class' => $this->modelName]);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        if (!$this->includesAreCorrect($request, new UserTransformer())) {
            $error['errors']['getIncludes'] = Lang::get('messages.NotExistInclude');
            return response()->json($error)->setStatusCode($this->status_codes['badrequest']);
        }

        $response = $this->response->item($user, new UserTransformer(), ['key' => $this->modelPlural]);
        $response = $this->applyMeta($response);
        return $response;
    }

    public function store($id, Request $request) 
    {
        $success = true;
        $code = 'conflict';
        $dataAssets = $dataDevices = $dataRoles = $dataUdls = array();

        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'users')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        /*
         * Now we can update the User.
         */
        try {
            $data = $request->all()['data'];
            $data['attributes']['id'] = $id;
            $user = $this->userInterface->update($data['attributes']);

            if ($user == 'notExist') {
                $success = false;
                $code = 'notexists';
                $error['errors']['user'] = Lang::get('messages.NotExistClass', ['class' => 'User']);
                //$error['errors']['Message'] = $e->getMessage();
            }

            if ($user == 'notSaved') {
                $success = false;
                $error['errors']['user'] = Lang::get('messages.NotSavedClass', ['class' => 'User']);
                //$error['errors']['Message'] = $e->getMessage();
            }

        } catch (\Exception $e) {
            $success = false;
            $error['errors']['users'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'User', 'option' => 'updated', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships']) && $success) {
            $dataRelationships = $data['relationships'];

            if (isset($dataRelationships['devicevariations']) && $success) {
                if (isset($dataRelationships['devicevariations']['data'])) {
                    $dataDevices = $this->parseJsonToArray($dataRelationships['devicevariations']['data'], 'devicevariations');
                    try {
                        $user->devicevariations()->sync($dataDevices);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['devicevariations'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'User', 'option' => 'updated', 'include' => 'DeviceVariations']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['roles']) && $success) {
                if (isset($dataRelationships['roles']['data'])) {
                    $dataRoles = $this->parseJsonToArray($dataRelationships['roles']['data'], 'roles');
                    try {
                        $user->roles()->sync($dataRoles);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['roles'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'User', 'option' => 'updated', 'include' => 'Roles']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['services']) && $success) {
                if (isset($dataRelationships['services']['data'])) {
                    $dataservices = $this->parseJsonToArray($dataRelationships['services']['data'], 'services');
                    try {
                        $user->services()->sync($dataservices);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['services'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'User', 'option' => 'updated', 'include' => 'Services']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['udls']) && $success) {
                if (isset($dataRelationships['udls']['data'])) {
                    $dataUdls = $this->parseJsonToArray($dataRelationships['udls']['data'], 'udls');
                    try {
                        $user->udlValues()->sync($dataUdls);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['udls'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'User', 'option' => 'updated', 'include' => 'Udls']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['addresses']) && $success) {
                if (isset($dataRelationships['addresses']['data'])) {
                    $dataAddress = $this->parseJsonToArray($dataRelationships['addresses']['data'], 'addresses');
                    try {
                        $user->addresses()->sync($dataAddress);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['addresses'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'User', 'option' => 'updated', 'include' => 'Address']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['allocations']) && $success) {
                if (isset($dataRelationships['allocations']['data'])) {
                    $data = $dataRelationships['allocations']['data'];

                    try {
                        $allocations = Allocation::where('userId', $id)->get();
                        $interfaceA = app()->make('WA\Repositories\Allocation\AllocationInterface');
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['allocations'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'User', 'option' => 'updated', 'include' => 'allocations']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }

                    if ($success) {
                        try {                           
                            $this->deleteNotRequested($data, $allocations, $interfaceA, 'allocations');

                            foreach ($data as $allocation) {
                                $allocation['userId'] = $user->id;
                                $allocation['companyId'] = $user->companyId;

                                if (isset($allocation['id'])) {
                                    if ($allocation['id'] == 0) {
                                        $interfaceA->create($allocation);
                                    } else {
                                        if ($allocation['id'] > 0) {
                                            $interfaceA->update($allocation);
                                        } else {
                                            $success = false;
                                            $error['errors']['allocations'] = 'the Allocation has an incorrect id';
                                        }
                                    }
                                } else {
                                    $success = false;
                                    $error['errors']['allocations'] = 'the Allocation has no id';
                                }
                            }
                        } catch (\Exception $e) {
                            $success = false;
                            $error['errors']['allocations'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'user', 'option' => 'updated', 'include' => 'allocations']);
                            //$error['errors']['Message'] = $e->getMessage();
                        }
                    }
                }
            }

            if (isset($dataRelationships['contents']) && $success) {
                if (isset($dataRelationships['contents']['data'])) {
                    $data = $dataRelationships['contents']['data'];

                    try {
                        $contents = Content::where('owner_id', $id)->get();
                        $interfaceC = app()->make('WA\Repositories\Content\ContentInterface');
                    } catch (\Exception $e) {
                        $success = false;                        
                        $error['errors']['contents'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'User', 'option' => 'updated', 'include' => 'contents']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }

                    if ($success) {
                        try {                           
                            $this->deleteNotRequested($data, $contents, $interfaceC, 'contents');

                            foreach ($data as $allocation) {
                                    
                                $allocation['owner_id'] = $user->id;

                                if (isset($allocation['id'])) {
                                    if ($allocation['id'] == 0) {
                                        $interfaceC->create($allocation);
                                    } else {
                                        if ($allocation['id'] > 0) {
                                            $interfaceC->update($allocation);
                                        } else {
                                            $success = false;
                                            $error['errors']['contents'] = 'the Content has an incorrect id';
                                        }
                                    }
                                } else {
                                    $success = false;
                                    $error['errors']['contents'] = 'the Content has no id';
                                }
                            }
                        } catch (\Exception $e) {
                            $success = false;
                            $error['errors']['contents'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'user', 'option' => 'updated', 'include' => 'contents']);
                            //$error['errors']['Message'] = $e->getMessage();
                        }
                    }
                }
            }
/*
            if (isset($dataRelationships['assets']) && $success) {
                if (isset($dataRelationships['assets']['data'])) {
                    $data = $dataRelationships['assets']['data'];

                    try {
                        $assets = Asset::where('userId', $id)->get();
                        $interfaceAss = app()->make('WA\Repositories\Asset\AssetInterface');
                    } catch (\Exception $e) {
                        $success = false;                        
                        $error['errors']['assets'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'User', 'option' => 'updated', 'include' => 'assets']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }

                    if ($success) {
                        try {                           
                            $this->deleteNotRequested($data, $assets, $interfaceAss, 'assets');

                            foreach ($data as $asset) {
                                    
                                $asset['userId'] = $user->id;

                                if (isset($asset['id'])) {
                                    if ($asset['id'] == 0) {
                                        $interfaceAss->create($asset);
                                    } else {
                                        if ($asset['id'] > 0) {
                                            $interfaceAss->update($asset);
                                        } else {
                                            $success = false;
                                            $error['errors']['assets'] = 'the Asset has an incorrect id';
                                        }
                                    }
                                } else {
                                    $success = false;
                                    $error['errors']['assets'] = 'the Asset has no id';
                                }
                            }
                        } catch (\Exception $e) {
                            $success = false;
                            $error['errors']['assets'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'user', 'option' => 'updated', 'include' => 'assets']);
                            //$error['errors']['Message'] = $e->getMessage();
                        }
                    }
                }
            }*/
        }

        if ($success) {
            DB::commit();
            return $this->response()->item($user, new UserTransformer(),
                ['key' => 'users'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes[$code]);
        }
    }

    public function create(Request $request) 
    {
        $success = true;
        $dataAssets = $dataDevices = $dataRoles = $dataUdls = array();

        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'users')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        /*
         * Now we can create the User.
         */
        try {
            $data = $request->all()['data'];

            if($this->userInterface->byEmail($data['attributes']['email'])['id'] > 0) {
                $error['errors']['User'] = 'The User can not be created, there are other user with the same email.';
                return response()->json($error)->setStatusCode(409);
            }

            $user = $this->userInterface->create($data['attributes']);
            if(!$user){
                $error['errors']['users'] = 'The User has not been created, some data information is wrong, may be the Email.';
                return response()->json($error)->setStatusCode(409);
            }
        } catch (\Exception $e) {
            $success = false;
            $error['errors']['users'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'User', 'option' => 'created', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships']) && $success) {
            $dataRelationships = $data['relationships'];

            if (isset($dataRelationships['devicevariations']) && $success) {
                if (isset($dataRelationships['devicevariations']['data'])) {
                    $dataDeviceVariations = $this->parseJsonToArray($dataRelationships['devicevariations']['data'], 'devicevariations');
                    try {
                        $user->devicevariations()->sync($dataDeviceVariations);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['devicevariations'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'User', 'option' => 'created', 'include' => 'DeviceVariations']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['roles']) && $success) {
                if (isset($dataRelationships['roles']['data'])) {
                    $dataRoles = $this->parseJsonToArray($dataRelationships['roles']['data'], 'roles');
                    try {
                        $user->roles()->sync($dataRoles);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['roles'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'User', 'option' => 'created', 'include' => 'Roles']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['udls']) && $success) {
                if (isset($dataRelationships['udls']['data'])) {
                    $dataUdls = $this->parseJsonToArray($dataRelationships['udls']['data'], 'udls');
                    try {
                        $user->udlValues()->sync($dataUdls);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['udls'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'User', 'option' => 'created', 'include' => 'Udls']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['addresses']) && $success) {
                if (isset($dataRelationships['addresses']['data'])) {
                    $dataAddress = $this->parseJsonToArray($dataRelationships['addresses']['data'], 'addresses');
                    try {
                        $user->addresses()->sync($dataAddress);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['addresses'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'User', 'option' => 'created', 'include' => 'Address']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['allocations']) && $success) {
                if (isset($dataRelationships['allocations']['data'])) {
                    $data = $dataRelationships['allocations']['data'];

                    try {
                        $interfaceAll = app()->make('WA\Repositories\Allocation\AllocationInterface');
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['allocations'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'User', 'option' => 'created', 'include' => 'Allocations']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }

                    if ($success) {
                        try {
                            foreach ($data as $allocation) {
                                $allocation['userId'] = $user->id;
                                $allocation['companyId'] = $user->companyId;
                                $interfaceAll->create($allocation);
                            }
                        } catch (\Exception $e) {
                            $success = false;
                            $error['errors']['allocations'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'User', 'option' => 'created', 'include' => 'Allocations']);
                            //$error['errors']['Message'] = $e->getMessage();
                        }
                    }
                }
            }

            if (isset($dataRelationships['contents']) && $success) {
                if (isset($dataRelationships['contents']['data'])) {
                    $data = $dataRelationships['contents']['data'];

                    try {
                        $interfaceC = app()->make('WA\Repositories\Content\ContentInterface');
                    } catch (\Exception $e) {
                        $success = false;                        
                        $error['errors']['contents'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'User', 'option' => 'created', 'include' => 'contents']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }

                    if ($success) {
                        try {                           
                            foreach ($data as $content) {
                                $content['owner_id'] = $user->id;
                                $interfaceC->create($content);
                            }
                        } catch (\Exception $e) {
                            $success = false;
                            $error['errors']['contents'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'user', 'option' => 'created', 'include' => 'contents']);
                            //$error['errors']['Message'] = $e->getMessage();
                        }
                    }
                }
            }
/*
            if (isset($dataRelationships['assets']) && $success) {
                if (isset($dataRelationships['assets']['data'])) {
                    $data = $dataRelationships['assets']['data'];

                    try {
                        $interfaceAss = app()->make('WA\Repositories\Asset\AssetInterface');
                    } catch (\Exception $e) {
                        $success = false;                        
                        $error['errors']['assets'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'User', 'option' => 'created', 'include' => 'Assets']);
                        $error['errors']['Message'] = $e->getMessage();
                    }

                    if ($success) {
                        try {                           
                            foreach ($data as $asset) {
                                $asset['userId'] = $user->id;
                                $interfaceAss->create($asset);
                            }
                        } catch (\Exception $e) {
                            $success = false;
                            $error['errors']['assets'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'User', 'option' => 'created', 'include' => 'Assets']);
                            $error['errors']['Message'] = $e->getMessage();
                        }
                    }
                }
            }*/
        }

        if ($success) {
            $code = bin2hex(random_bytes(64));
            $url = $this->request['url'];
            $redirectPath = $url.'/acceptUser/'.$user->identification.'/'.$code;

            $data = [
                'identification' => $user->identification,
                'redirectPath' => $redirectPath,
            ];

            $mail = Mail::send('emails.auth.register', $data, function ($m) use ($user) {
                $m->from(env('MAIL_FROM_ADDRESS'), 'Wireless Analytics');
                $m->to($user->email)->subject('New User '.$user->username.' !');
            });

            DB::commit();
            Cache::put('user_email_'.$code, $user->identification, 60);
            Cache::put('user_code_'.$user->identification, $code, 60);
            return $this->response()->item($user, new UserTransformer(), ['key' => 'users'])
                ->setStatusCode($this->status_codes['created']);    
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    public function delete($id) {
        $user = User::find($id);
        if ($user <> null) {
            $this->userInterface->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'User']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $user = User::find($id);
        if ($user == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'User']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /*public function getLoggedInUser(Request $request)
    {
        $user = Auth::user();

        if ($user === null) {
            $error['errors']['scopes'] = 'There\'s no user authenticated';
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $transformer = $user->getTransformer();

        if (!$this->includesAreCorrect($request, $transformer)) {
            $error['errors']['getIncludes'] = Lang::get('messages.NotExistInclude');
            return response()->json($error)->setStatusCode($this->status_codes['badrequest']);
        }

        $response = $this->response->item($user, $transformer, ['key' => 'users']);
        return $response;
    }*/

    private function transformToJson($array) {
        $list = [];
        foreach ($array as $obj) {
            $aux['id'] = $obj->id;
            $aux['type'] = 'packages';
            $aux['attributes']['name'] = $obj->name;
            $aux['attributes']['information'] = $obj->information;
            $aux['attributes']['companyId'] = $obj->companyId;

            array_push($list, $aux);
        }

        $final['data'] = $list;
        return response()->json($final)->setStatusCode($this->status_codes['ok']);
    }

    private function getUdlValuesFromUser($user) {
        $udlV = $user->udlValues;
        $arrayAux = [];
        foreach ($udlV as $uv) {
            $aux['udlName'] = $uv->udl->name;
            $aux['udlValue'] = $uv->name;
            array_push($arrayAux, $aux);
        }

        return $arrayAux;
    }

    private function checkUserAttributes($user, $conditions) {
        $isOk = true;
        if (count($conditions) > 0) {
            foreach ($conditions as $cond) {
                if ($cond['name'] == 'Supervisor?') {
                    $isOk = $isOk && $this->checkIfIsSupervisor($user->isSupervisor, $cond);
                }
                // ADD Other Attributes if needed.
            }

            if ($isOk) {
                return true;
            }

            return false;
        }        

        return true;
    }

    private function checkIfIsSupervisor($supervisor, $condition) {
        if (strtolower($condition->condition) == 'equal') {
            if (strtolower($condition->value) == 'no') {
                if ($supervisor == 0) {
                    return true;
                }
            } else if (strtolower($condition->value) == 'yes') {
                if ($supervisor == 1) {
                    return true;
                }
            }
        } else if (strtolower($condition->condition) == 'not equal') {
            if (strtolower($condition->value) == 'no') {
                if ($supervisor == 1) {
                    return true;
                }
            } else if (strtolower($condition->value) == 'yes') {
                if ($supervisor == 0) {
                    return true;
                }
            }
        }

        return false;
    }

    private function checkIfHasAnyAddress($address, $conditions) {
        if (count($conditions) > 0) {
            foreach ($address as $add) {
                $ok = true;
                $ok = $ok && $this->checkIfHasAnyInfo($add->city, $conditions, 'City');
                $ok = $ok && $this->checkIfHasAnyInfo($add->state, $conditions, 'State');
                $ok = $ok && $this->checkIfHasAnyInfo($add->country, $conditions, 'Country');
                if ($ok) {
                    return true;
                }
            }
            if(count($address) > 0) {
                return false;
            }
        }
        return true;
    }

    private function checkIfHasAnyInfo($val, $conditions, $type){
        $conditionsOK = true;
        foreach ($conditions as $cond) {
            if ($cond['name'] == $type) {
                if ($cond['condition'] == 'contains') {
                    if (strpos(strtolower($val), strtolower($cond['value'])) !== false) {
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else if ($cond['condition'] == 'equal') {
                    if (strtolower($cond['value']) == strtolower($val)) {
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else if ($cond['condition'] == 'not equal') {
                    if (strtolower($cond['value']) != strtolower($val)) {
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else {
                    // NOTHING
                }
            }
        }
        if ($conditionsOK) {
            return true;
        }
        return false;
    }

    private function checkIfHasAnyUdl($udl, $conditions) {
        $conditionsOK = true;

        foreach ($conditions as $cond) {
            if ($cond['name'] == $udl['udlName']) {
                if ($cond['condition'] == 'contains') {
                    if (strpos(strtolower($udl['udlValue']), strtolower($cond['value'])) !== false) {
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else if ($cond['condition'] == 'equal') {
                    if (strtolower($udl['udlValue']) == strtolower($cond['value'])) {
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else if ($cond['condition'] == 'not equal') {
                    if (strtolower($udl['udlValue']) != strtolower($cond['value'])) {
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else if ($cond['condition'] == 'greater than') {
                    if (strtolower($udl['udlValue']) > strtolower($cond['value'])) {
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else if ($cond['condition'] == 'greater or equal') {
                    if (strtolower($udl['udlValue']) >= strtolower($cond['value'])) {
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else if ($cond['condition'] == 'less than') {
                    if (strtolower($udl['udlValue']) < strtolower($cond['value'])){
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else if ($cond['condition'] == 'less or equal') {
                    if (strtolower($udl['udlValue']) <= strtolower($cond['value'])) {
                        $conditionsOK = $conditionsOK && true;
                    } else {
                        $conditionsOK = $conditionsOK && false;
                    }
                } else {
                    // NOTHING
                }
            }
        }

        if ($conditionsOK) {
            return true;
        }
        return false;
    }
}
