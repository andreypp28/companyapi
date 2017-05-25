<?php

namespace WA\Repositories\UserNotifications;

use WA\Repositories\RepositoryInterface;

interface UserNotificationsInterface extends RepositoryInterface
{
    /**
     * Return by categoryId.
     *
     * @param $categoryId
     *
     * @return mixed
     */
    public function byCategoryId($categoryId);

    /**
     * Get Notification Types based on CategoryId.
     *
     * @param $categoryId
     *
     * @return mixed
     */
    public function getDistinctNotificationTypesByCategory($categoryId);

    /**
     * Get Users by Category and Type of Notification.
     *
     * @param $type
     * @param $categoryId
     *
     * @return mixed
     */
    public function byTypeAndCategoryId($type, $categoryId);
}
