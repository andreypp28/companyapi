<?php

namespace WA\Repositories\NotificationCategory;

use WA\Repositories\AbstractRepository;

class EloquentNotificationCategory extends AbstractRepository implements NotificationCategoryInterface
{
    /**
     * Return Notification Categories by Category Id.
     *
     * @param $categoryId
     *
     * @return mixed
     */
    public function byCategory($categoryId)
    {
        $response = $this->model->where('id', $categoryId);

        return $response->get();
    }

    /**
     * Return Notification Categories by Category Name.
     *
     * @param $name
     *
     * @return mixed
     */
    public function byName($name)
    {
        $response = $this->model->where('name', $name);

        return $response->first();
    }
}
