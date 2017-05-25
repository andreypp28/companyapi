<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Modification\Modification;
use WA\DataStore\Modification\ModificationTransformer;
use WA\Repositories\Modification\ModificationInterface;

/**
 * Modification resource.
 *
 * @Resource("Modification", uri="/Modification")
 */
class ModificationsController extends FilteredApiController
{
    /**
     * @var modificationInterface
     */
    protected $modification;

    /**
     * ModificationsController constructor.
     *
     * @param ModificationInterface $modification
     * @param Request $request
     */
    public function __construct(ModificationInterface $modification, Request $request)
    {
        parent::__construct($modification, $request);
        $this->modification = $modification;
    }

    /**
     * Update contents of a modification.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        if ($this->isJsonCorrect($request, 'modifications')) {
            try {
                $data = $request->all()['data'];
                $data['attributes']['id'] = $id;
                $modification = $this->modification->update($data['attributes']);

                if ($modification == 'notExist') {
                    $error['errors']['modification'] = Lang::get('messages.NotExistClass', ['class' => 'Modification']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['notexists']);
                }

                if ($modification == 'notSaved') {
                    $error['errors']['modification'] = Lang::get('messages.NotSavedClass', ['class' => 'Modification']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                }

                return $this->response()->item($modification, new ModificationTransformer(),
                    ['key' => 'modifications'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['modifications'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Modification', 'option' => 'updated', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Create a new modification.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        if ($this->isJsonCorrect($request, 'modifications')) {
            try {
                $data = $request->all()['data']['attributes'];
                $modification = $this->modification->create($data);

                return $this->response()->item($modification, new ModificationTransformer(),
                    ['key' => 'modifications'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['modifications'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Modification', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Delete a modification.
     *
     * @param $id
     */
    public function delete($id)
    {
        $modification = Modification::find($id);
        if ($modification != null) {
            $this->modification->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Modification']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $modification = Modification::find($id);
        if ($modification == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Modification']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
