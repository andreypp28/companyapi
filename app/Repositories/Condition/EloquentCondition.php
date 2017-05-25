<?php

namespace WA\Repositories\Condition;

use WA\Repositories\AbstractRepository;

/**
 * Class EloquentCondition.
 */
class EloquentCondition extends AbstractRepository implements ConditionInterface
{
    /**
     * Update Condition.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $condition = $this->model->find($data['id']);

        if (!$condition) {
            return 'notExist';
        }

        if (isset($data['packageId'])) {
            $condition->packageId = $data['packageId'];
        }
        if (isset($data['nameCond'])) {
            $condition->name = $data['nameCond'];
        }
        if (isset($data['condition'])) {
            $condition->condition = $data['condition'];
        }
        if (isset($data['value'])) {
            $condition->value = $data['value'];
        }

        if (!$condition->save()) {
            return 'notSaved';
        }

        return $condition;
    }

    /**
     * Get an array of all the available Condition.
     *
     * @return array of Condition
     */
    public function getAllCondition()
    {
        $condition = $this->model->all();

        return $condition;
    }

    /**
     * Create a new Condition.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $conditionData = [
            "packageId" =>  isset($data['packageId']) ? $data['packageId'] : null ,
            "name" => isset($data['nameCond']) ? $data['nameCond'] : null,
            "condition" => isset($data['condition']) ? $data['condition'] : null,
            "value" => isset($data['value']) ? $data['value'] : null,
        ];

        $condition = $this->model->create($conditionData);

        if (!$condition) {
            return false;
        }

        return $condition;
    }

    /**
     * Delete a Condition.
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
