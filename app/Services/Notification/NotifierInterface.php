<?php

namespace WA\Services\Notification;

/**
 * Interface NotifierInterface.
 */
interface NotifierInterface
{
    /**
     * Send the notification.
     *
     * @param $subject
     * @param $message
     *
     * @return mixed
     */
    public function notify($subject, $message);
}
