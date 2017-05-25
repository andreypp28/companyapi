<?php

namespace WA\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\DeviceVariation\DeviceVariation;
use WA\DataStore\Device\Device;
use WA\DataStore\Device\DeviceTransformer;
use WA\Repositories\Device\DeviceInterface;

/**
 * Devices resource.
 *
 * @Resource("Devices", uri="/devices")
 */
class DevicesController extends FilteredApiController
{
    /**
     * @var DeviceInterface
     */
    protected $device;

    public $returnEmptyResults = true;

    /**
     * Package Controller constructor
     *
     * @param DeviceInterface $device
     * @param Request $request
     */
    public function __construct(DeviceInterface $device, Request $request)
    {
        parent::__construct($device, $request);
        $this->device = $device;
    }

    /**
     * Update contents of a device
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        $success = true;
        $dataImages = $dataModifications = array();

        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'devices')) {
            //$error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        /*
         * Now we can update the Device.
         */
        try {
            $data = $request->all()['data'];
            $data['attributes']['id'] = $id;
            $device = $this->device->update($data['attributes']);

            if ($device == 'notExist') {
                DB::rollBack();
                $error['errors']['device'] = Lang::get('messages.NotExistClass', ['class' => 'Device']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['notexists']);
            }

            if ($device == 'notSaved') {
                DB::rollBack();
                $error['errors']['device'] = Lang::get('messages.NotSavedClass', ['class' => 'Device']);
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['devices'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Device', 'option' => 'updated', 'include' => '']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships'])) {
            $dataRelationships = $data['relationships'];

            if (isset($dataRelationships['images'])) {
                if (isset($dataRelationships['images']['data'])) {
                    $dataImages = $this->parseJsonToArray($dataRelationships['images']['data'], 'images');
                    try {
                        $device->images()->sync($dataImages);
                    } catch (\Exception $e) {
                        $error['errors']['images'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Device', 'option' => 'updated', 'include' => 'Images']);
                    }
                }
            }

            if (isset($dataRelationships['modifications'])) {
                if (isset($dataRelationships['modifications']['data'])) {
                    $dataModifications = $this->parseJsonToArray($dataRelationships['modifications']['data'],
                        'modifications');
                    try {
                        $device->modifications()->sync($dataModifications);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['modifications'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Device', 'option' => 'updated', 'include' => 'Modifications']);
                    }
                }
            }

            if (isset($dataRelationships['devicevariations'])) {
                if (isset($dataRelationships['devicevariations']['data'])) {

                    if ($success) {
                        try {    
                            $deviceVar = DeviceVariation::where('deviceId', $id)->get();
                                                   
                            $deviceVariationInterface = app()->make('WA\Repositories\DeviceVariation\DeviceVariationInterface');
                            $this->deleteNotRequested($dataRelationships['devicevariations']['data'], $deviceVar, $deviceVariationInterface, 'devicevariations');

                            $helper = app()->make('WA\Http\Controllers\DeviceVariationsHelperController');
                            $success=$helper->store($dataRelationships['devicevariations'],$device->id);
                            if (!$success){ 
                                $error['errors']['devicevariations'] = Lang::get('messages.NotOptionIncludeClass',['class' => 'Device', 'option' => 'updated', 'include' => 'DeviceVariations']);
                            }
                            
                        } catch (\Exception $e) {
                            $success = false;
                            $error['errors']['devicevariations'] = Lang::get('messages.NotOptionIncludeClass',
                                ['class' => 'Device', 'option' => 'updated', 'include' => 'DeviceVariations']);
                            $error['errors']['Message'] = $e->getMessage();
                        }
                    } else {
                        $success = false;
                        $error['errors']['devicevariations'] = Lang::get('messages.NotIncludeExistsOptionClass',
                            ['class' => 'Device', 'option' => 'updated', 'include' => 'Devicevariations']);
                        
                    }
                }
            }
        }

        if ($success) {
            DB::commit();
            return $this->response()->item($device, new DeviceTransformer(),
                ['key' => 'devices'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /**
     * Create a new device
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {   
        $success = true;
        $dataImages =  $dataModifications = array();

        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'devices')) { 
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        } else {
            $data = $request->all()['data'];
            $dataType = $data['type'];
            $dataAttributes = $data['attributes'];
        }

        DB::beginTransaction();

        /*
         * Now we can create the Device.
         */
        try {
            $device = $this->device->create($dataAttributes);
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['devices'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Device', 'option' => 'created', 'include' => '']);
            $error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships'])) {

            $dataRelationships = $data['relationships'];

            if (isset($dataRelationships['images'])) {
                if (isset($dataRelationships['images']['data'])) {
                    $dataImages = $this->parseJsonToArray($dataRelationships['images']['data'], 'images');
                    try {
                        $device->images()->sync($dataImages);
                    } catch (\Exception $e) {
                        $error['errors']['images'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Device', 'option' => 'created', 'include' => 'Images']);
                    }
                }
            }

            if (isset($dataRelationships['modifications'])) {
                if (isset($dataRelationships['modifications']['data'])) {
                    $dataModifications = $this->parseJsonToArray($dataRelationships['modifications']['data'],
                        'modifications');
                    try {
                        $device->modifications()->sync($dataModifications);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['modifications'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Device', 'option' => 'created', 'include' => 'Modifications']);
                        
                    }
                }
            }

            if (isset($dataRelationships['devicevariations'])) {
                if (isset($dataRelationships['devicevariations']['data'])) {

                    if ($success) {
                        try {
                            $helper = app()->make('WA\Http\Controllers\DeviceVariationsHelperController');

                            
                                    $helper->create($dataRelationships['devicevariations'], $device->id);
                            
                        } catch (\Exception $e) {

                            $success = false;
                            $errors = 'Device Variation not created.';
                           
                            $error['errors']['Message'] = $e->getMessage();
                        }
                    } else {
                        $success = false;
                        $error['errors']['devicevariations'] = Lang::get('messages.NotIncludeExistsOptionClass',
                            ['class' => 'Device', 'option' => 'created', 'include' => 'DeviceVariations']);
                        
                    }
                }
            }
        }

        if ($success) {
            DB::commit();
            return $this->response()->item($device, new DeviceTransformer(),
                ['key' => 'devices'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    private function deleteRepeat($data)
    {
        $dataAux = array();

        for ($j = 0; $j < count($data); $j++) {

            if ($dataAux == null) {
                array_push($dataAux, $data[$j]);
            } else {
                $save = true;

                for ($k = 0; $k < count($dataAux); $k++) {

                    $esIgual = true;

                    if (isset($dataAux[$k]['carrierId']) && isset($data[$j]['carrierId'])) {
                        if ($dataAux[$k]['carrierId'] <> $data[$j]['carrierId']) {
                            $esIgual = $esIgual && false;
                        }    
                    }
                    if (isset($dataAux[$k]['deviceId']) && isset($data[$j]['deviceId'])) {
                        if ($dataAux[$k]['deviceId'] <> $data[$j]['deviceId']) {
                            $esIgual = $esIgual && false;
                        }
                    }
                    if (isset($dataAux[$k]['companyId']) && isset($data[$j]['companyId'])) {
                        if ($dataAux[$k]['companyId'] <> $data[$j]['companyId']) {
                            $esIgual = $esIgual && false;
                        }
                    }

                    if ($esIgual) {
                        $save = false;
                        break;
                    } else {
                        $save = $save && true;
                    }
                }

                if ($save) {
                    array_push($dataAux, $data[$j]);
                }
            }
        }
        return $dataAux;
    }

    private function checkIfDeviceVariationsRowIsCorrect($deviceVariations, $deviceId)
    {   $modInterface = app()->make('WA\Repositories\Device\DeviceInterface');

        if (isset($deviceVariations['deviceId'])) {

            $device = $modInterface->byId($mod);
                $reflectorResponse = new \ReflectionClass($device);
                $classResponse = $reflectorResponse->getProperty('attributes');
                $classResponse->setAccessible(true);
                $dataResponse = $classResponse->getValue($device);
            if ($deviceVariations['deviceId'] == $dataResponse['id']) {
                    return array("bool" => true, "error" => "No Error", "id" => 0);
            }
            else{
                return array("bool" => false, "error" => "Id Not Found", "id" => $deviceVariations['deviceId']);
            }
        }

    }

    /**
     * Delete a device
     *
     * @param $id
     */
    public function delete($id)
    {
        $device = Device::find($id);
        if ($device <> null) {
            $this->device->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Device']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $device = Device::find($id);
        if ($device == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Device']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
