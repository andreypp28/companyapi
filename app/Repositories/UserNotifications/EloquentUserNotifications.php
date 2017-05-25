<?php

namespace WA\Repositories\UserNotifications;

use WA\Repositories\AbstractRepository;

class EloquentUserNotifications extends AbstractRepository implements UserNotificationsInterface
{
    /**
     * Return by categoryId.
     *
     * @param $categoryId
     *
     * @return mixed
     */
    public function byCategoryId($categoryId)
    {
        $response = $this->model->where('id', $categoryId);

        return $response->get();
    }

    /**
     * Get Notification Types based on CategoryId.
     *
     * @param $categoryId
     *
     * @return mixed
     */
    public function getDistinctNotificationTypesByCategory($categoryId)
    {
        $response = $this->model->distinct()->select('type')->where('categoryId', $categoryId);

        return $response->get(['type'])->toArray();
    }

    /**
     * Get Users by Category and Type of Notification.
     *
     * @param $type
     * @param $categoryId
     *
     * @return mixed
     */
    public function byTypeAndCategoryId($type, $categoryId)
    {
        $response = $this->model->select('employeeId')->where('categoryId', $categoryId)->where('type', $type);

        return $response->get(['employeeId'])->toArray();
    }
}
