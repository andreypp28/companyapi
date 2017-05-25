<?php

namespace WA\Repositories\Condition;

use WA\Repositories\AbstractRepository;

/**
 * Class EloquentConditionOperator.
 */
class EloquentConditionOperator extends AbstractRepository implements ConditionOperatorInterface
{
    /**
     * Update ConditionOperator.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $conditionOperator = $this->model->find($data['id']);

        if (!$conditionOperator) {
            return 'notExist';
        }

        $conditionOperator->originalName = isset($data['originalName']) ? $data['originalName'] : null;
        $conditionOperator->apiName = isset($data['apiName']) ? $data['apiName'] : null;

        if (!$Condition->save()) {
            return 'notSaved';
        }

        return $Condition;
    }

    /**
     * Get the conditionOperator Id tied to the Condition.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getConditionOperatorId($id)
    {
        return $this->model->where('id', $id)->first()->companies;
    }

    /**
     * Get an array of all the available ConditionOperator.
     *
     * @return array of conditionOperator
     */
    public function getAllConditionOperators()
    {
        $conditionOperator = $this->model->all();

        return $conditionOperator;
    }

    /**
     * Create new ConditionOperator.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $conditionOperatorData = [
        'originalName' => !empty($data['originalName']) ? $data['originalName'] : null,
        'apiName' => isset($data['apiName']) ? $data['apiName'] : null,
        ];

        $conditionOperator = $this->model->create($conditionOperatorData);

        if (!$conditionOperator) {
            return false;
        }

        return $conditionOperator;
    }

    /**
     * Delete ConditionOperator.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true)
    {
        if (!$this->model->find($id)) {
            return false;
        }

        if (!$soft) {
            $this->model->forceDelete($id);
        }

        return $this->model->destroy($id);
    }
}
