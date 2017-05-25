<?php

namespace WA\Repositories\Condition;

use WA\Repositories\AbstractRepository;

/**
 * Class EloquentConditionField.
 */
class EloquentConditionField extends AbstractRepository implements ConditionFieldInterface
{
    /**
     * Update ConditionField.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $conditionField = $this->model->find($data['id']);

        if (!$conditionField) {
            return 'notExist';
        }

        $conditionField->typeField = isset($data['typeField']) ? $data['typeField'] : null;
        $conditionField->field = isset($data['field']) ? $data['field'] : null;

        if (!$Condition->save()) {
            return 'notSaved';
        }

        return $Condition;
    }

    /**
     * Get the conditionField Id tied to the Condition.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getConditionFieldId($id)
    {
        return $this->model->where('id', $id)->first()->companies;
    }

    /**
     * Get an array of all the available ConditionField.
     *
     * @return array of conditionField
     */
    public function getAllConditionField()
    {
        $conditionField = $this->model->all();

        return $conditionField;
    }

    /**
     * Create new ConditionField.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $conditionFieldData = [
        'typeField' => !empty($data['typeField']) ? $data['typeField'] : null,
        'field' => isset($data['field']) ? $data['field'] : null,
        ];

        $conditionField = $this->model->create($conditionFieldData);

        if (!$conditionField) {
            return false;
        }

        return $conditionField;
    }

    /**
     * Delete ConditionField.
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
