<?php

namespace WA\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Carrier\Carrier;
use WA\DataStore\Carrier\CarrierTransformer;
use WA\Repositories\Carrier\CarrierInterface;

/**
 * Carrier resource.
 *
 * @Resource("carrier", uri="/carriers")
 */
class CarriersController extends FilteredApiController
{
    /**
     * @var CarrierInterface
     */
    protected $carrier;

    /**
     * CarriersController constructor.
     *
     * @param CarrierInterface $carrier
     * @param Request $request
     */
    public function __construct(CarrierInterface $carrier, Request $request)
    {
        parent::__construct($carrier, $request);
        $this->carrier = $carrier;
    }

    /**
     * Update contents of a Carrier.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'carriers')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        try {
            $data = $request->all()['data'];
            $data['attributes']['id'] = $id;
            $carrier = $this->carrier->update($data['attributes']);

            if ($carrier == 'notExist') {
                DB::rollBack();
                $error['errors']['carrier'] = Lang::get('messages.NotExistClass', ['class' => 'Carrier']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['notexists']);
            }

            if ($carrier == 'notSaved') {
                DB::rollBack();
                $error['errors']['carrier'] = Lang::get('messages.NotSavedClass', ['class' => 'Carrier']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['carriers'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Carrier', 'option' => 'updated', 'include' => '']);
            $error['errors']['carriersMessage'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        if (isset($data['relationships'])) {
            if (isset($data['relationships']['images'])) {
                if (isset($data['relationships']['images']['data'])) {
                    try {
                        $dataImages = $this->parseJsonToArray($data['relationships']['images']['data'], 'images');
                        $carrier->images()->sync($dataImages);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $error['errors']['images'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Carrier', 'option' => 'created', 'include' => 'Images']);
                        $error['errors']['Message'] = $e->getMessage();
                        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                    }
                }
            }
        }

        DB::commit();
        return $this->response()->item($carrier, new CarrierTransformer(),
            ['key' => 'carriers'])->setStatusCode($this->status_codes['created']);
    }

    /**
     * Create a new Carrier.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'carriers')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        try {
            $data = $request->all()['data'];
            $carrier = $this->carrier->create($data['attributes']);
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['carriers'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Carrier', 'option' => 'created', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        if (isset($data['relationships'])) {
            if (isset($data['relationships']['images'])) {
                if (isset($data['relationships']['images']['data'])) {
                    try {
                        $dataImages = $this->parseJsonToArray($data['relationships']['images']['data'], 'images');
                        $carrier->images()->sync($dataImages);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $error['errors']['images'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Carrier', 'option' => 'created', 'include' => 'Images']);
                        //$error['errors']['Message'] = $e->getMessage();
                        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                    }
                }
            }
        }

        DB::commit();
        return $this->response()->item($carrier, new CarrierTransformer(),
            ['key' => 'carriers'])->setStatusCode($this->status_codes['created']);
    }

    /**
     * Delete a Carrier.
     *
     * @param $id
     */
    public function delete($id)
    {
        $carrier = Carrier::find($id);
        if ($carrier <> null) {
            $this->carrier->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Carrier']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $carrier = Carrier::find($id);
        if ($carrier == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Carrier']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
