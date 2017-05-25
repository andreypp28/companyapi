<?php

namespace WA\Repositories\Condition;

use WA\Repositories\RepositoryInterface;

/**
 * Interface ContentInterface.
 */
interface ConditionFieldInterface extends RepositoryInterface
{
    /**
     * Update ConditionField.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Get the company Id tied to ConditionField.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getConditionFieldId($id);

    /**
     * Get an array of all the available ConditionField.
     *
     * @return array of ConditionFields
     */
    public function getAllConditionField();

    /**
     * Create new ConditionField.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Delete ConditionField.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
