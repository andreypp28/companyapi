<?php

namespace WA\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Preset\Preset;
use WA\DataStore\Preset\PresetTransformer;
use WA\Repositories\Category\PresetInterface;

/**
 * Preset resource.
 *
 * @Resource("preset", uri="/preset")
 */
class PresetsController extends FilteredApiController
{
    /**
     * @var PresetInterface
     */
    protected $preset;

    /**
     * PresetsController constructor.
     *
     * @param PresetInterface $preset
     * @param Request $request
     */
    public function __construct(PresetInterface $preset, Request $request)
    {
        parent::__construct($preset, $request);
        $this->preset = $preset;
    }

    /**
     * Update contents of a Preset.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        $success = true;
        $datadevicevariations = array();
        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'presets')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');

            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        try {
            $data = $request->all()['data'];
            $data['attributes']['id'] = $id;
            $preset = $this->preset->update($data['attributes']);

            if ($preset == 'notExist') {
                DB::rollBack();
                $error['errors']['preset'] = Lang::get('messages.NotExistClass', ['class' => 'Preset']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['notexists']);
            }

            if ($preset == 'notSaved') {
                DB::rollBack();
                $error['errors']['preset'] = Lang::get('messages.NotSavedClass', ['class' => 'Preset']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['preset'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Preset', 'option' => 'updated', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        if (isset($data['relationships'])) {

            if (isset($data['relationships']['devicevariations'])) {
                if (isset($data['relationships']['devicevariations']['data'])) {
                    try {
                        $datadevicevariations = $this->parseJsonToArray($data['relationships']['devicevariations']['data'], 'devicevariations');
                        $preset->devicevariations()->sync($datadevicevariations);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['devicevariations'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Preset', 'option' => 'created', 'include' => 'devicevariations']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }
        }

        if ($success) {
            DB::commit();

            return $this->response()->item($preset, new PresetTransformer(),
                ['key' => 'presets'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();

            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /**
     * Create a new Preset.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        $success = true;
        $datadevicevariations = array();
        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'presets')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');

            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        try {
            $data = $request->all()['data'];
            $preset = $this->preset->create($data['attributes']);
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['preset'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Preset', 'option' => 'created', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
        if (isset($data['relationships'])) {
             if (isset($data['relationships']['devicevariations'])) {
                if (isset($data['relationships']['devicevariations']['data'])) {
                    try {
                        $datadevicevariations = $this->parseJsonToArray($data['relationships']['devicevariations']['data'], 'devicevariations');
                        $preset->devicevariations()->sync($datadevicevariations);
                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['devicevariations'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'Preset', 'option' => 'created', 'include' => 'devicevariations']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }
        }

        if ($success) {
            DB::commit();

            return $this->response()->item($preset, new PresetTransformer(),
                ['key' => 'presets'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();

            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /**
     * Delete a Preset.
     *
     * @param $id
     */
    public function delete($id)
    {
        $preset = Preset::find($id);
        if ($preset != null) {
            $this->preset->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Preset']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $preset = Preset::find($id);
        if ($preset == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Preset']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
