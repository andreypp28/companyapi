<?php

namespace WA\Repositories\Condition;

use WA\Repositories\RepositoryInterface;

/**
 * Interface ConditionInterface.
 */
interface ConditionInterface extends RepositoryInterface
{
    /**
     * Get Array of all Conditions.
     *
     * @return array of Condition
     */
    public function getAllCondition();

    /**
     * Create Condition.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update Condition.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete Condition.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
