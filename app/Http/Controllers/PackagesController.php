<?php

namespace WA\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Condition\Condition;
use WA\DataStore\Package\Package;
use WA\DataStore\Package\PackageTransformer;
use WA\DataStore\User\User;
use WA\Repositories\Package\PackageInterface;
use WA\Repositories\User\UserInterface;

use Authorizer;
use Log;

/**
 * Package resource.
 *
 * @Resource("Package", uri="/Package")
 */
class PackagesController extends FilteredApiController
{
    /**
     * @var PackageInterface
     */
    protected $package;

    /**
     * PackagesController constructor.
     *
     * @param PackageInterface $package
     * @param Request $request
     */
    public function __construct(UserInterface $user, PackageInterface $package, Request $request)
    {
        parent::__construct($package, $request);
        $this->package = $package;
        $this->userInterface = $user;
    }

    public function userPackages(Request $request)
    {
        $req = $request->all();

        if (!isset($req['data'])) {
            $error['errors']['data'] = Lang::get('messages.InvalidJson');
            //Log::debug("JSON NO VALID - data");
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        } else {
            $data = $request['data'];
            if (!isset($data['companyId'])) {
                $error['errors']['companyId'] = Lang::get('messages.InvalidJson');
                //Log::debug("JSON NO VALID - companyId");
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            } 

            if (!isset($data['conditions'])) {
                $error['errors']['conditions'] = Lang::get('messages.InvalidJson');
                //Log::debug("JSON NO VALID - conditions");
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            }
        }

        $info = $req['data'];
        $companyId = $info['companyId'];
        $conditions = $info['conditions'];

        $users = $this->userInterface->byCompanyId($companyId)->all();

        $numberUsers = 0;
        foreach ($users as $user) {
            $userInfo = User::find($user->id);
            $address = $userInfo->addresses;
            $udlValues = $this->getUdlValuesFromUser($userInfo);

            $isOk = true;
            //Log::debug("user: ".print_r($user, true));
            $isOk = $isOk && $this->checkIfIsSupervisor($userInfo->isSupervisor, $conditions);
            //Log::debug("isOk IsSupervisor: ".print_r($isOk, true));
            $isOk = $isOk && $this->checkIfHasAnyAddress($address, $conditions);
            //Log::debug("isOk Address: ".print_r($isOk, true));
            foreach ($udlValues as $udl) {
                $isOk = $isOk && $this->checkIfHasAnyUdl($udl, $conditions);
                //Log::debug("isOk: ".print_r($isOk, true));
            }

            if ($isOk) {
                $numberUsers ++;
                //Log::debug("numberUsers: ".print_r($numberUsers, true));
            }
        }

        $result['number'] = $numberUsers;
        return response()->json($result)->setStatusCode($this->status_codes['ok']);
    }

    private function getUdlValuesFromUser($user) {
        $udlV = $user->udlValues;
        $arrayAux = [];
        foreach ($udlV as $uv) {
            $aux['udlName'] = $uv->udl->name;
            $aux['udlValue'] = $uv->name;
            array_push($arrayAux, $aux);
            //Log::debug("getUdlValuesFromUser: uv->name: ".print_r($uv->name, true));
            //Log::debug("getUdlValuesFromUser: uv->udl->name: ".print_r($uv->udl->name, true));
        }

        //Log::debug("getUdlValuesFromUser: arrayAux: ".print_r($arrayAux, true));
        return $arrayAux;
    }

    private function checkIfIsSupervisor($supervisor, $conditions) {
        if (count($conditions) > 0) {
            foreach ($conditions as $cond) {
                if ($cond['nameCond'] == 'Supervisor?') {
                    if (strtolower($cond['value']) == strtolower('Yes')) {
                        if ($supervisor == 1) {
                            return true;
                        }
                    } else if (strtolower($cond['value']) == strtolower('No')) {
                        if ($supervisor == 0) {
                            return true;
                        }
                    } else {
                        // NOTHING.   
                    }
                    return false;
                }
            }    
        }        

        return true;
    }

    private function checkIfHasAnyAddress($address, $conditions) {
        //Log::debug("Count conditions: ".print_r(count($conditions), true));
        //Log::debug("Count address: ".print_r(count($address), true));
        if (count($conditions) > 0) {
            foreach ($address as $add) {
                $ok = true;
                $ok = $ok && $this->checkIfHasAnyInfo($add->city, $conditions, 'City');
                //Log::debug("ok City: ".print_r($ok, true));
                $ok = $ok && $this->checkIfHasAnyInfo($add->state, $conditions, 'State');
                //Log::debug("ok State: ".print_r($ok, true));
                $ok = $ok && $this->checkIfHasAnyInfo($add->country, $conditions, 'Country');
                //Log::debug("ok Country: ".print_r($ok, true));
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
            if ($cond['nameCond'] == $type) {
                if ($cond['condition'] == 'contains') {
                    //Log::debug("Value: ".print_r($cond['value'], true));
                    //Log::debug("val: ".print_r($val, true));
                    //Log::debug("Strpos: ".print_r(strpos($cond['value'], $val), true));
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
    // allConditions: ['contains', 'greater than', 'greater or equal', 'less than', 'less or equal', 'equal', 'not equal'],
    private function checkIfHasAnyUdl($udl, $conditions) {
        $conditionsOK = true;
        //Log::debug("conditions: ".print_r($conditions, true));
        foreach ($conditions as $cond) {
            //Log::debug("COND: ".print_r($cond, true));
            //Log::debug("UDL: ".print_r($udl, true));
            
            if ($cond['nameCond'] == $udl['udlName']) {
                //Log::debug("udl[udlValue]: ".print_r($udl['udlValue'], true));
                //Log::debug("cond[value]: ".print_r($cond['value'], true));
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

    /**
     * Update contents of a Package.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        $success = true;
        $dataConditions = $dataServices = $dataDevices = $dataApps = array();
        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'packages')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }


        DB::beginTransaction();

        /*
         * Now we can create the Package.
         */
        try {
            $data = $request->all()['data'];
            $data['attributes']['id'] = $id;
            $package = $this->package->update($data['attributes']);

            if ($package == 'notExist') {
                DB::rollBack();
                $error['errors']['package'] = Lang::get('messages.NotExistClass', ['class' => 'Package']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['notexists']);
            }

            if ($package == 'notSaved') {
                DB::rollBack();
                $error['errors']['package'] = Lang::get('messages.NotSavedClass', ['class' => 'Package']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $success = false;
            $error['errors']['packages'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Package', 'option' => 'updated', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships'])) {
            $dataRelationships = $data['relationships'];

            if (isset($data['relationships']) && $success) {
                if (isset($data['relationships']['conditions'])) {
                    if (isset($data['relationships']['conditions']['data'])) {
                        $data = $data['relationships']['conditions']['data'];

                        try {
                            $conditions = Condition::where('packageId', $id)->get();
                            $conditionsInterface = app()->make('WA\Repositories\Condition\ConditionInterface');
                        } catch (\Exception $e) {
                            $succes = false;
                            $error['errors']['conditions'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'Package', 'option' => 'updated', 'include' => 'conditions']);
                            //$error['errors']['Message'] = $e->getMessage();
                        }

                        if ($success) {
                            $this->deleteNotRequested($data, $conditions, $conditionsInterface, 'conditions');

                            foreach ($data as $item) {
                                $item['packageId'] = $package->id;

                                if (isset($item['id'])) {
                                    if ($item['id'] == 0) {
                                        $conditionsInterface->create($item);
                                    } else {
                                        if ($item['id'] > 0) {
                                            $conditionsInterface->update($item);
                                        } else {
                                            $success = false;
                                            $error['errors']['items'] = 'the Condition has an incorrect id';
                                        }
                                    }
                                } else {
                                    $success = false;
                                    $error['errors']['conditions'] = 'the Condition has no id';
                                }
                            }
                        }                    
                    }
                }
            } else {
                foreach ($serviceItems as $item) {
                    $serviceItemsInterface->deleteById($item['id']);
                }
            }

            if (isset($dataRelationships['services'])) {
                if (isset($dataRelationships['services']['data'])) {
                    $dataServices = $this->parseJsonToArray($dataRelationships['services']['data'], 'services');
                    try {
                        $package->services()->sync($dataServices);
                    } catch (\Exception $e) {
                        $error['errors']['services'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Package', 'option' => 'updated', 'include' => 'Services']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['devicevariations'])) {
                if (isset($dataRelationships['devicevariations']['data'])) {
                    $dataDevices = $this->parseJsonToArray($dataRelationships['devicevariations']['data'], 'devicevariations');
                    try {
                        $package->devicevariations()->sync($dataDevices);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['devicevariations'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Package', 'option' => 'updated', 'include' => 'DeviceVariations']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['apps'])) {
                if (isset($dataRelationships['apps']['data'])) {
                    $dataApps = $this->parseJsonToArray($dataRelationships['apps']['data'], 'apps');
                    try {
                        $package->apps()->sync($dataApps);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['apps'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Package', 'option' => 'updated', 'include' => 'Apps']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['addresses']) && $success) {
                if (isset($dataRelationships['addresses']['data'])) {
                    $dataAddress = $this->parseJsonToArray($dataRelationships['addresses']['data'], 'addresses');
                    try {
                        $package->addresses()->sync($dataAddress);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['addresses'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Package', 'option' => 'created', 'include' => 'Address']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }
        }

        if ($success) {
            DB::commit();
            return $this->response()->item($package, new PackageTransformer(),
                ['key' => 'packages'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /**
     * Create a new Package.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        $success = true;
        $dataConditions = $dataServices = $dataDevices = $dataApps = $dataDelivery = array();

        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'packages')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        /*
         * Now we can create the Package.
         */
        try {
            $data = $request->all()['data'];
            $package = $this->package->create($data['attributes']);
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['packages'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Package', 'option' => 'created', 'include' => '']);
            //Log::debug($e->getMessage());
            $error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships'])) {
            $dataRelationships = $data['relationships'];

            if (isset($data['relationships']['conditions'])) {
                if (isset($data['relationships']['conditions']['data'])) {
                        
                    $conditionsInterface = app()->make('WA\Repositories\Condition\ConditionInterface');
                    $data = $data['relationships']['conditions']['data'];

                    foreach ($data as $item) {
                        if (isset($item['type']) && $item['type'] == 'conditions') {
                            try {
                                $item['packageId'] = $package->id;
                                $conditionsInterface->create($item);
                            } catch (\Exception $e) {
                                DB::rollBack();
                                $error['errors']['conditions'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'Package', 'option' => 'created', 'include' => 'Conditions']);
                                $error['errors']['Message'] = $e->getMessage();
                                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                            }
                        } else {
                            $success = false;
                            $error['errors']['conditions'] = Lang::get('messages.NotOptionIncludeClass',
                                ['class' => 'Package', 'option' => 'created', 'include' => 'Conditions']);
                        }
                    }
                }
            }

            if (isset($dataRelationships['services'])) {
                if (isset($dataRelationships['services']['data'])) {
                    $dataServices = $this->parseJsonToArray($dataRelationships['services']['data'], 'services');
                    try {
                        $package->services()->sync($dataServices);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['services'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Package', 'option' => 'created', 'include' => 'Services']);
                        $error['errors']['Message'] = $e->getMessage();
                    }
                }
            }
            
            if (isset($dataRelationships['devicevariations'])) {
                if (isset($dataRelationships['devicevariations']['data'])) {
                    $dataDevices = $this->parseJsonToArray($dataRelationships['devicevariations']['data'], 'devicevariations');
                    try {
                        $package->devicevariations()->sync($dataDevices);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['devicevariations'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Package', 'option' => 'updated', 'include' => 'DeviceVariations']);
                        $error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['apps'])) {
                if (isset($dataRelationships['apps']['data'])) {
                    $dataApps = $this->parseJsonToArray($dataRelationships['apps']['data'], 'apps');
                    try {
                        $package->apps()->sync($dataApps);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['apps'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Package', 'option' => 'created', 'include' => 'Apps']);
                        $error['errors']['Message'] = $e->getMessage();
                    }
                }
            }

            if (isset($dataRelationships['addresses']) && $success) {
                if (isset($dataRelationships['addresses']['data'])) {
                    $dataAddress = $this->parseJsonToArray($dataRelationships['addresses']['data'], 'addresses');
                    try {
                        $package->addresses()->sync($dataAddress);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['addresses'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Package', 'option' => 'created', 'include' => 'Address']);
                        $error['errors']['Message'] = $e->getMessage();
                    }
                }
            }
        }

        if ($success) {
            DB::commit();
            return $this->response()->item($package, new PackageTransformer(),
                ['key' => 'packages'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /**
     * Delete a Package
     *
     * @param $id
     */
    public function delete($id)
    {
        $package = Package::find($id);
        if ($package != null) {
            $this->package->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Package']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $package = Package::find($id);

        if ($package == null) {
            return array('success' => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Package']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
