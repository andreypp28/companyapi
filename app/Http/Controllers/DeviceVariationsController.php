<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\DeviceVariation\DeviceVariation;
use WA\DataStore\DeviceVariation\DeviceVariationTransformer;
use WA\Repositories\DeviceVariation\DeviceVariationInterface;

use DB;

/**
 * DeviceVariation resource.
 *
 * @Resource("DeviceVariation", uri="/devicevariations")
 */
class DeviceVariationsController extends FilteredApiController
{
    /**
     * @var DeviceVariationInterface
     */
    protected $deviceVariation;

    /**
     * DeviceVariationsController constructor.
     *
     * @param DeviceVariationInterface $deviceVariation
     * @param Request $request
     */
    public function __construct(DeviceVariationInterface $deviceVariation, Request $request)
    {
        parent::__construct($deviceVariation, $request);
        $this->deviceVariation = $deviceVariation;
    }

    /**
     * Update contents of a DeviceVariation.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        $success = true;
         $dataModifications = $dataPresets =array();


        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'devicevariations')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        /*
         * Now we can create the DeviceVariation.
         */
        try {
            $data = $request->all()['data'];
            $data['attributes']['id'] = $id;
            $deviceVariation = $this->deviceVariation->update($data['attributes']);

            if ($deviceVariation == 'notExist') {
                DB::rollBack();
                $error['errors']['deviceVariation'] = Lang::get('messages.NotExistClass', ['class' => 'DeviceVariation']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['notexists']);
            }

            if ($deviceVariation == 'notSaved') {
                DB::rollBack();
                $error['errors']['deviceVariation'] = Lang::get('messages.NotSavedClass', ['class' => 'DeviceVariation']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $success = false;
            $error['errors']['deviceVariations'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'DeviceVariation', 'option' => 'updated', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships'])) {
            $dataRelationships = $data['relationships'];

            if (isset($dataRelationships['modifications'])) {
                if (isset($dataRelationships['modifications']['data'])) {
                    $dataModifications = $this->parseJsonToArray($dataRelationships['modifications']['data'], 'modifications');
                    try {
                        $deviceVariation->modifications()->sync($dataModifications);
                    } catch (\Exception $e) {
                        $error['errors']['modifications'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'DeviceVariation', 'option' => 'updated', 'include' => 'Modifications']);
                        //$error['errors']['ModificationsMessage'] = $e->getMessage();
                    }
                }
            }
            if (isset($dataRelationships['images'])) {
                if (isset($dataRelationships['images']['data'])) {
                    $dataImages = $this->parseJsonToArray($dataRelationships['images']['data'], 'images');
                    try {
                        $device->images()->sync($dataImages);
                    } catch (\Exception $e) {
                        $error['errors']['images'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'DeviceVariation', 'option' => 'updated', 'include' => 'Images']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }
        }

        if ($success) {
            DB::commit();
            return $this->response()->item($deviceVariation, new DeviceVariationTransformer(),
                ['key' => 'devicevariations'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /**
     * Create a new DeviceVariation.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        $success = true;
        $dataModifications = $dataPresets =array();

        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'devicevariations')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        /*
         * Now we can create the DeviceVariation.
         */
        try {
            $data = $request->all()['data'];
            $deviceVariation = $this->deviceVariation->create($data['attributes']);
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['deviceVariations'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'DeviceVariation', 'option' => 'created', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships'])) {
            $dataRelationships = $data['relationships'];

            if (isset($dataRelationships['modifications'])) {
                if (isset($dataRelationships['modifications']['data'])) {
                    $dataModifications = $this->parseJsonToArray($dataRelationships['modifications']['data'], 'modifications');
                    try {
                        $deviceVariation->modifications()->sync($dataModifications);
                    } catch (\Exception $e) {
                        $error['errors']['modifications'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'DeviceVariation', 'option' => 'updated', 'include' => 'Modifications']);
                        //$error['errors']['ModificationsMessage'] = $e->getMessage();
                    }
                }
            }
            if (isset($dataRelationships['images'])) {
                if (isset($dataRelationships['images']['data'])) {
                    $dataImages = $this->parseJsonToArray($dataRelationships['images']['data'], 'images');
                    try {
                        $device->images()->sync($dataImages);
                    } catch (\Exception $e) {
                        $error['errors']['images'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'DeviceVariation', 'option' => 'created', 'include' => 'Images']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }
        }

        if ($success) {
            DB::commit();
            return $this->response()->item($deviceVariation, new DeviceVariationTransformer(),
                ['key' => 'devicevariations'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /**
     * Delete a DeviceVariation.
     *
     * @param $id
     */
    public function delete($id)
    {
        $deviceVariation = DeviceVariation::find($id);
        if ($deviceVariation != null) {
            $this->deviceVariation->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'DeviceVariation']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }


        $deviceVariation = DeviceVariation::find($id);
        if ($deviceVariation == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'DeviceVariation']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
