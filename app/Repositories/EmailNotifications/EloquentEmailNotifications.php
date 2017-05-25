<?php

namespace WA\Repositories\EmailNotifications;

use WA\Repositories\AbstractRepository;
use Carbon\Carbon;
use Log;

class EloquentEmailNotifications extends AbstractRepository implements EmailNotificationsInterface
{
    /**
     * Add an entry to the table.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $emailNotification = [
            'user_id' => isset($data['user_id']) ? $data['user_id'] : null,
            'category_id' => isset($data['category_id']) ? $data['category_id'] : null,
            'data' => isset($data['data']) ? $data['data'] : '',
        ];

        try {
            $email_notification = $this->model->create($emailNotification);

            if (!$email_notification) {
                return false;
            }

            return $email_notification;
        } catch (\Exception $e) {
            Log::error('[ '.get_class().' ] | There was an issue: '.$e->getMessage());
        }
    }

    /**
     * Update Sent column of the table when an email is sent out.
     *
     * @param $id
     *
     * @return mixed
     */
    public function updateSentTime($id)
    {
        $emailNotification = $this->model->find((int) $id);

        $emailNotification->sent_at = Carbon::now();

        if (!$emailNotification->save()) {
            return false;
        }

        return true;
    }
}
