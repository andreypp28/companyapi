<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\DeviceType\DeviceType;
use WA\DataStore\DeviceType\DeviceTypeTransformer;
use WA\Repositories\DeviceType\DeviceTypeInterface;

/**
 * DeviceType resource.
 *
 * @Resource("DeviceType", uri="/DeviceType")
 */
class DeviceTypesController extends FilteredApiController
{
    /**
     * @var DeviceTypeInterface
     */
    protected $deviceType;

    /**
     * DeviceTypesController constructor.
     *
     * @param DeviceTypeInterface $deviceType
     * @param Request $request
     */
    public function __construct(DeviceTypeInterface $deviceType, Request $request)
    {
        parent::__construct($deviceType, $request);
        $this->deviceType = $deviceType;
    }

    /**
     * Update contents of a DeviceType.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        if ($this->isJsonCorrect($request, 'devicetypes')) {
            try {
                $data = $request->all()['data'];
                $data['attributes']['id'] = $id;
                $devicetype = $this->deviceType->update($data['attributes']);

                if ($devicetype == 'notExist') {
                    $error['errors']['devicetype'] = Lang::get('messages.NotExistClass', ['class' => 'DeviceType']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['notexists']);
                }

                if ($devicetype == 'notSaved') {
                    $error['errors']['devicetype'] = Lang::get('messages.NotSavedClass', ['class' => 'DeviceType']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                }

                return $this->response()->item($devicetype, new DeviceTypeTransformer(),
                    ['key' => 'devicetypes'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['devicetypes'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'DeviceType', 'option' => 'updated', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Create a new DeviceType.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        if ($this->isJsonCorrect($request, 'devicetypes')) {
            try {
                $data = $request->all()['data']['attributes'];
                $devicetype = $this->deviceType->create($data);

                return $this->response()->item($devicetype, new DeviceTypeTransformer(),
                    ['key' => 'devicetypes'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['devicetypes'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'DeviceType', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Delete an DeviceType.
     *
     * @param $id
     */
    public function delete($id)
    {
        $deviceType = DeviceType::find($id);
        if ($deviceType <> null) {
            $this->deviceType->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'DeviceType']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $deviceType = DeviceType::find($id);
        if ($deviceType == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'DeviceType']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
