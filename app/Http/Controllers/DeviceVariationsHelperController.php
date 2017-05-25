<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\DeviceVariation\DeviceVariation;
use WA\DataStore\DeviceVariation\DeviceVariationTransformer;
use WA\Repositories\DeviceVariation\DeviceVariationInterface;
use Log;
use DB;

/**
 * DeviceVariation resource.
 *
 * @Resource("DeviceVariation", uri="/devicevariations")
 */
class DeviceVariationsHelperController extends FilteredApiController
{
    protected $deviceVariation;

    /**
     * DeviceVariationsController constructor.
     *
     * @param DeviceVariationInterface $deviceVariation
     * @param Request $request
     */
    public function __construct(DeviceVariationInterface $deviceVariation)
    {
        //parent::__construct($deviceVariation, $request);
        $this->deviceVariation = $deviceVariation;
    }
    
    /**
     * Update contents of a DeviceVariation.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($data, $deviceId)
    {
        DB::beginTransaction();
        foreach ($data['data'] as $var) {
            $aux['data'] = $var;    
            if (!$this->isJsonCorrect($aux, 'devicevariations')) {
                return false;
            }
            
            $aux['data']['attributes']['deviceId']=$deviceId;
            if($aux['data']['id']>0){
                $aux['data']['attributes']['id'] = $aux['data']['id'];
                $deviceVariation = $this->deviceVariation->update($aux['data']['attributes']);
                if ($deviceVariation == 'notExist') {
                    DB::rollBack();
                    return false;
                }

                if ($deviceVariation == 'notSaved') {
                    DB::rollBack();
                    return false;
                }
            }elseif($aux['data']['id']==0){
                try{
                    $deviceVariation = $this->deviceVariation->create($aux['data']['attributes']);
                }catch (\Exception $e) {
                    DB::rollBack();
                    return false;
                }
            }
            else{
                DB::rollBack();
                return false;
            }

            if(isset($aux['data']['relationships'])){
                $dataRelationships = $aux['data']['relationships'];
                if (isset($dataRelationships['modifications'])) {
                    if (isset($dataRelationships['modifications']['data'])) {
                        $dataModifications = $this->parseJsonToArray($dataRelationships['modifications']['data'],
                            'modifications');
                        try {
                            $deviceVariation->modifications()->sync($dataModifications);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return false;
                        }
                    }
                }

                if (isset($dataRelationships['images'])) {
                    if (isset($dataRelationships['images']['data'])) {
                        $dataImages = $this->parseJsonToArray($dataRelationships['images']['data'], 'images');
                        try {
                            $deviceVariation->images()->sync($dataImages);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return false;
                        }
                    }
                }
            }
        }
        DB::commit();
        return true;
    }

    /**
     * Create a new DeviceVariation.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create($var, $id)
    {


        $success = true;
        $dataModifications = array();        

        DB::beginTransaction();
        try {
            foreach ($var['data'] as $data) {

                $aux['data'] = $data;

                if (!$this->isJsonCorrect($aux, 'devicevariations')) { 

                    return false;
                }

                $data['attributes']['deviceId'] = $id;
                $deviceVariation = $this->deviceVariation->create($data['attributes']);
                if (isset($data['relationships']['modifications'])) {
                    if (isset($data['relationships']['modifications']['data'])) {
                        $dataDeviceVariations = $this->parseJsonToArray($data['relationships']['modifications']['data'], 'modifications');
                        try {
                            $deviceVariation->modifications()->sync($dataDeviceVariations);
                        } catch (\Exception $e) {
                            DB::rollBack();
                             return false;
                        }
                    }
                }
                if (isset($data['relationships']['images'])) {
                    if (isset($data['relationships']['images']['data'])) {
                        $dataDeviceVariationsI = $this->parseJsonToArray($data['relationships']['images']['data'], 'images');
                        try {
                            $deviceVariation->images()->sync($dataDeviceVariationsI);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return false;
                         }
                    }
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        

        if ($success) {
            DB::commit();
            return $this->response()->item($deviceVariation, new DeviceVariationTransformer(),
                ['key' => 'devicevariations'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return false;
        }
    }
}
