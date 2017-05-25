<?php

namespace WA\Services\Notification;

/**
 * Interface NotificationInterface.
 */
interface NotificationInterface
{
    /**
     * @param $categoryId
     * @param array $data
     *
     * @return mixed
     */
    public function notify($categoryId, array $data);
}
