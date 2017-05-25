<?php

namespace WA\Repositories\NotificationCategory;

use WA\Repositories\RepositoryInterface;

interface NotificationCategoryInterface extends RepositoryInterface
{
    /**
     * Return Notification Categories by Category Id.
     *
     * @param $categoryId
     *
     * @return mixed
     */
    public function byCategory($categoryId);

    /**
     * Return Notification Categories by Category Name.
     *
     * @param $name
     *
     * @return mixed
     */
    public function byName($name);
}
