<?php

namespace WA\Repositories\Condition;

use WA\Repositories\RepositoryInterface;

/**
 * Interface ContentInterface.
 */
interface ConditionOperatorInterface extends RepositoryInterface
{
    /**
     * Update ConditionOperator.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Get the company Id tied to ConditionOperator.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getConditionOperatorId($id);

    /**
     * Get an array of all the available ConditionOperator.
     *
     * @return array of ConditionOperators
     */
    public function getAllConditionOperators();

    /**
     * Create new ConditionOperator.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Delete ConditionOperator.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
