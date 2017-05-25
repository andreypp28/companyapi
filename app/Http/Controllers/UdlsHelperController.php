<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Udl\Udl;
use WA\DataStore\Udl\UdlTransformer;
use WA\Repositories\Udl\UdlInterface;
use WA\DataStore\UdlValue\UdlValue;
use WA\DataStore\UdlValue\UdlValueTransformer;
use WA\Repositories\UdlValue\UdlValueInterface;

use Log;
use DB;

/**
 * Udl resource.
 *
 * @Resource("Udl", uri="/Udls")
 */
class UdlsHelperController extends FilteredApiController
{
    protected $udl;

    /**
     * UdlsController constructor.
     *
     * @param UdlInterface $Udl
     * @param Request $request
     */
    public function __construct(UdlInterface $udl)
    {
        $this->udl = $udl;
    }
    
    /**
     * Update contents of a Udl.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create($data, $companyId)
    {
        DB::beginTransaction();

        foreach ($data['data'] as $var) {
            $aux['data'] = $var;

            if (!$this->isJsonCorrect($aux, 'udls')) {
                return false;
            }
            
            $aux['data']['attributes']['companyId'] = $companyId;
            if ($aux['data']['id'] > 0) {
                $aux['data']['attributes']['id'] = $aux['data']['id'];
                $udl = $this->udl->update($aux['data']['attributes']);
                if ($udl == 'notExist') {
                    DB::rollBack();
                    return false;
                }

                if ($udl == 'notSaved') {
                    DB::rollBack();
                    return false;
                }
            } else if ($aux['data']['id'] == 0) {
                try{
                    $udl = $this->udl->create($aux['data']['attributes']);
                } catch (\Exception $e) {
                    DB::rollBack();                 
                    return false;
                }
            } else {
                DB::rollBack();
                return false;
            }

            if (isset($aux['data']['relationships'])) {
                if (isset($aux['data']['relationships']['udlvalues'])) {
                    $data = $aux['data']['relationships']['udlvalues'];
                    if (isset($data['data'])) {

                        try {
                            $udlvalues = UdlValue::where('udlId', $udl['id'])->get();
                            $udlvaluesInterface = app()->make('WA\Repositories\UdlValue\UdlValueInterface');
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return false;
                        }

                        $this->deleteNotRequested($data['data'], $udlvalues, $udlvaluesInterface, 'udlvalues');

                        foreach ($data['data'] as $item) {
                            $item['udlId'] = $udl->id;

                            if (isset($item['id'])) {
                                if ($item['id'] == 0) {
                                    $udlvaluesInterface->create($item);
                                } else {
                                    if ($item['id'] > 0) {
                                        $udlvaluesInterface->update($item);
                                    } else {
                                        DB::rollBack();
                                        return false;
                                    }
                                }
                            } else {
                                DB::rollBack();
                                return false;
                            }
                        }
                    }       
                }
            }
        }
        DB::commit();
        return true;
    }
}
