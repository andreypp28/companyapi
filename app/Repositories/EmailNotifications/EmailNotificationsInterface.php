<?php

namespace WA\Repositories\EmailNotifications;

use WA\Repositories\RepositoryInterface;

interface EmailNotificationsInterface extends RepositoryInterface
{
    /**
     * Add an entry to the table.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update Sent column of the table when an email is sent out.
     *
     * @param $id
     *
     * @return mixed
     */
    public function updateSentTime($id);
}
