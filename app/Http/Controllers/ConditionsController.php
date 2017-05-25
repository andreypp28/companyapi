<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Condition\Condition;
use WA\DataStore\Condition\ConditionTransformer;
use WA\Repositories\Condition\ConditionInterface;

/**
 * Condition resource.
 *
 * @Resource("condition", uri="/conditions")
 */
class ConditionsController extends FilteredApiController
{
    /**
     * @var ConditionInterface
     */
    protected $condition;

    /**
     * Condition Controller constructor.
     *
     * @param ConditionInterface $condition
     */
    public function __construct(ConditionInterface $condition, Request $request)
    {
        parent::__construct($condition, $request);
        $this->condition = $condition;
    }

    /**
     * Update contents of a Condition.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        if ($this->isJsonCorrect($request, 'conditions')) {
            try {
                $data = $request->all()['data']['attributes'];
                $data['id'] = $id;
                $condition = $this->condition->update($data);

                if ($condition == 'notExist') {
                    $error['errors']['condition'] = Lang::get('messages.NotExistClass', ['class' => 'Condition']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['notexists']);
                }

                if ($condition == 'notSaved') {
                    $error['errors']['condition'] = Lang::get('messages.NotSavedClass', ['class' => 'Condition']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                }

                return $this->response()->item($condition, new ConditionTransformer(),
                    ['key' => 'conditions'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['conditions'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Condition', 'option' => 'updated', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Create a new Condition.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        if ($this->isJsonCorrect($request, 'conditions')) {
            try {
                $data = $request->all()['data']['attributes'];
                $condition = $this->condition->create($data);

                return $this->response()->item($condition, new ConditionTransformer(),
                    ['key' => 'conditions'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['conditions'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Condition', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Delete an Condition.
     *
     * @param $id
     */
    public function delete($id)
    {
        $condition = Condition::find($id);
        if ($condition <> null) {
            $this->condition->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Condition']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $condition = Condition::find($id);
        if ($condition == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Condition']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
