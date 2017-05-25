<?php

namespace WA\Services\Notification;

use Log;
use WA\Repositories\UserNotifications\UserNotificationsInterface;

/**
 * Class Notifier
 * @package WA\Services\Notification
 */
class Notifier
{
    public function __construct(UserNotificationsInterface $userNotifications)
    {
        $this->employeeNotifications = $userNotifications;
    }

    /**
     * @param $categoryId
     * @param array $data
     *
     * @return bool
     */
    public function processNotification($categoryId, array $data)
    {
        try {
            //Get types of notification,i.e, how to send the notifications (Ex: Email)
            $notification_types = $this->employeeNotifications->getDistinctNotificationTypesByCategory($categoryId);
            if (!empty($notification_types)) {
                foreach ($notification_types as $type) {
                    $this->sendNotification($type['type'], $categoryId, $data);
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Process Notification failed: '.$e->getMessage());
            return false;
        }
    }

    /**
     * @param $type
     * @param $categoryId
     * @param array $data
     *
     * @return mixed
     *
     * @throws \Exception
     */
    private function sendNotification($type, $categoryId, array $data)
    {
        try {
            $namespace = __NAMESPACE__.'\\';
            $type = $type.'Notification';
            $notify_class = $namespace.ucfirst(camel_case($type));

            if (!class_exists($notify_class)) {
                throw new \Exception("Notification not defined for '$type' ");
            }

            $class = app()->make($notify_class);

            return $class->notify($categoryId, $data);
        } catch (\Exception $e) {
            Log::error('Calling Notifying class failed: '.$e->getMessage());
            return false;
        }
    }
}
