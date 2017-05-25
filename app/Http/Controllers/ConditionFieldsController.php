<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Condition\ConditionField;
use WA\DataStore\Condition\ConditionFieldTransformer;
use WA\Repositories\Condition\ConditionFieldInterface;

/**
 * ConditionField resource.
 *
 * @Resource("conditionfield", uri="/condition/fieldss")
 */
class ConditionFieldsController extends FilteredApiController
{
    /**
     * @var ConditionFieldInterface
     */
    protected $conditionField;

    /**
     * ConditionField Controller constructor.
     *
     * @param ConditionFieldInterface $conditionField
     */
    public function __construct(ConditionFieldInterface $conditionField, Request $request)
    {
        parent::__construct($conditionField, $request);
        $this->conditionField = $conditionField;
    }

    /**
     * Update contents of a ConditionField.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        if ($this->isJsonCorrect($request, 'conditionFields')) {
            try {
                $data = $request->all()['data']['attributes'];
                $data['id'] = $id;
                $conditionField = $this->conditionField->update($data);

                if ($conditionField == 'notExist') {
                    $error['errors']['conditionField'] = Lang::get('messages.NotExistClass',
                        ['class' => 'ConditionField']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['notexists']);
                }

                if ($conditionField == 'notSaved') {
                    $error['errors']['conditionField'] = Lang::get('messages.NotSavedClass',
                        ['class' => 'ConditionField']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                }

                return $this->response()->item($conditionField, new ConditionFieldTransformer(),
                    ['key' => 'conditionfields'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['conditionFields'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'ConditionField', 'option' => 'updated', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Create a new ConditionField.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        if ($this->isJsonCorrect($request, 'conditionFields')) {
            try {
                $data = $request->all()['data']['attributes'];
                $conditionField = $this->conditionField->create($data);

                return $this->response()->item($conditionField, new ConditionFieldTransformer(),
                    ['key' => 'conditionfields'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['conditionFields'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'ConditionField', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Delete an ConditionField.
     *
     * @param $id
     */
    public function delete($id)
    {
        $conditionField = ConditionField::find($id);
        if ($conditionField <> null) {
            $this->conditionField->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'ConditionField']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $conditionField = ConditionField::find($id);
        if ($conditionField == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'ConditionField']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
