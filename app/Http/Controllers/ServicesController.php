<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\ServiceItem\ServiceItem;
use WA\DataStore\Service\Service;
use WA\DataStore\Service\ServiceTransformer;
use WA\Repositories\Service\ServiceInterface;

use DB;
use Log;

/**
 * Service resource.
 *
 * @Resource("Service", uri="/services")
 */
class ServicesController extends FilteredApiController
{
    /**
     * @var ServiceInterface
     */
    protected $service;

    /**
     * ServicesController constructor.
     *
     * @param ServiceInterface $service
     * @param Request $request
     */
    public function __construct(ServiceInterface $service, Request $request)
    {
        parent::__construct($service, $request);
        $this->service = $service;
    }

    /**
     * Update contents of a Service.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        $success = true;
        $dataServiceItemsFromRequest = $dataServiceItemsFromDB = array();

        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'services')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        /*
         * Now we can update the Service.
         */
        try {
            $data = $request->all()['data'];
            $data['attributes']['id'] = $id;
            $service = $this->service->update($data['attributes']);

            if ($service == 'notExist') {
                $success = false;
                $code = 'notexists';
                $error['errors']['service'] = Lang::get('messages.NotExistClass', ['class' => 'Service']);
                //$error['errors']['Message'] = $e->getMessage();
            }

            if ($service == 'notSaved') {
                $success = false;
                $error['errors']['service'] = Lang::get('messages.NotSavedClass', ['class' => 'Service']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } catch (\Exception $e) {
            $succes = false;
            $error['errors']['services'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Service', 'option' => 'updated', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        try {
            $serviceItems = ServiceItem::where('serviceId', $id)->get();
            $serviceItemsInterface = app()->make('WA\Repositories\ServiceItem\ServiceItemInterface');
        } catch (\Exception $e) {
            $error['errors']['serviceitems'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'Service', 'option' => 'updated', 'include' => 'ServiceItems']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships']) && $success) {
            if (isset($data['relationships']['serviceitems'])) {
                if (isset($data['relationships']['serviceitems']['data'])) {
                    $data = $data['relationships']['serviceitems']['data'];

                    try {
                        $serviceItems = ServiceItem::where('serviceId', $id)->get();
                        $serviceItemsInterface = app()->make('WA\Repositories\ServiceItem\ServiceItemInterface');
                    } catch (\Exception $e) {
                        $succes = false;
                        $error['errors']['serviceitems'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'Service', 'option' => 'updated', 'include' => 'ServiceItems']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }

                    if ($success) {
                        $this->deleteNotRequested($data, $serviceItems, $serviceItemsInterface, 'serviceitems');

                        foreach ($data as $item) {
                            $item['serviceId'] = $service->id;
                            $item['companyId'] = $service->companyId;

                            if (isset($item['id'])) {
                                if ($item['id'] == 0) {
                                    $serviceItemsInterface->create($item);
                                } else {
                                    if ($item['id'] > 0) {
                                        $serviceItemsInterface->update($item);
                                    } else {
                                        $success = false;
                                        $error['errors']['items'] = 'the ServiceItem has an incorrect id';
                                    }
                                }
                            } else {
                                $success = false;
                                $error['errors']['serviceitems'] = 'the ServiceItem has no id';
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

        if ($success) {
            DB::commit();
            return $this->response()->item($service, new ServiceTransformer(), ['key' => 'services'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /**
     * Create a new Service.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        DB::beginTransaction();

        if ($this->isJsonCorrect($request, 'services')) {
            try {
                $data = $request->all()['data'];
                $service = $this->service->create($data['attributes']);
            } catch (\Exception $e) {
                DB::rollBack();
                $error['errors']['services'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Service', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            }

            if (isset($data['relationships'])) {
                if (isset($data['relationships']['packages'])) {
                    if (isset($data['relationships']['packages']['data'])) {
                        $datapackages = $this->parseJsonToArray($data['relationships']['packages']['data'], 'packages');
                        try {
                            $service->packages()->sync($datapackages);
                        } catch (\Exception $e) {
                            $success = false;
                            $error['errors']['packages'] = Lang::get('messages.NotOptionIncludeClass',
                                ['class' => 'Service', 'option' => 'updated', 'include' => 'Packages']);
                            //$error['errors']['Message'] = $e->getMessage();
                        }
                    }
                }
                if (isset($data['relationships']['serviceitems'])) {
                    if (isset($data['relationships']['serviceitems']['data'])) {
                        
                        $serviceItemsInterface = app()->make('WA\Repositories\ServiceItem\ServiceItemInterface');
                        $data = $data['relationships']['serviceitems']['data'];

                        foreach ($data as $item) {
                            try {
                                $item['serviceId'] = $service->id;
                                $serviceItemsInterface->create($item);    
                            } catch (\Exception $e) {
                                DB::rollBack();
                                $error['errors']['services'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'Service', 'option' => 'created', 'include' => 'ServiceItems']);
                                //$error['errors']['Message'] = $e->getMessage();
                                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                                
                            }
                        }
                    }
                }
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::commit();
        return $this->response()->item($service, new ServiceTransformer(), ['key' => 'services'])->setStatusCode($this->status_codes['created']);
    }

    /**
     * Delete a Service.
     *
     * @param $id
     */
    public function delete($id)
    {
        $service = Service::find($id);
        if ($service != null) {
            $this->service->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Service']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $service = Service::find($id);
        if ($service == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Service']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
