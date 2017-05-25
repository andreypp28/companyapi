<?php

namespace WA\Services\Notification;

use Illuminate\Mail\Mailer as Mailer;
use WA\Repositories\EmailNotifications\EmailNotificationsInterface;
use WA\Repositories\NotificationCategory\NotificationCategoryInterface;
use WA\Repositories\UserNotifications\UserNotificationsInterface;
use WA\Repositories\User\UserInterface;

use Log;

/**
 * Class EmailNotification.
 */
class EmailNotification implements NotificationInterface
{
    protected $email_template = "emails.notifications.";

    public function __construct(
        Mailer $mailer,
        EmailNotificationsInterface $emailNotifications,
        NotificationCategoryInterface $notificationCategory,
        UserInterface $userInterface,
        UserNotificationsInterface $userNotifications
    ) {
        $this->mailer = $mailer;
        $this->emailNotifications = $emailNotifications;
        $this->notificationCategory = $notificationCategory;
        $this->employeeInterface = $userInterface;
        $this->employeeNotifications = $userNotifications;
    }

    /**
     * Send the notification as an email.
     *
     * @param $categoryId
     * @param array $data
     *
     * @return mixed
     */
    public function notify($categoryId, array $data = [])
    {
        //Get information on categoryId
        $category = $this->notificationCategory->byId($categoryId);

        //Get template and subject for this email
        $view = $this->email_template . $category->name;
        $subject = $category->text;

        try {
            //Get the users to be notified by email for this category
            $userIds = $this->employeeNotifications->byTypeAndCategoryId('email', $category->id);
            $users = array();
            foreach ($userIds as $uid) {
                $user = $this->employeeInterface->byId($uid['employeeId']);
                $user->categoryId = $category->id;
                array_push($users, $user);
            }

            if (!empty($users)) {
                $this->sendEmails($categoryId, $users, $view, $subject, $data);
            }

            return true;
        } catch (\Exception $e) {
            //\Log::error('Notifying by email failed: ' . $e->getMessage());

            return false;
        }
    }

    public function sendEmails($categoryId, $users, $view, $subject, $data)
    {
        //Notify the Users
        foreach ($users as $user) {
            //Add an entry
            $email_data['user_id'] = $user->id;
            $email_data['category_id'] = $categoryId;
            $email_data['data'] = json_encode($data);
            $notification = $this->emailNotifications->create($email_data);

            try {
                //Send the email
                $this->mailer->send(
                    $view,
                    $data,
                    function ($message) use ($subject, $user) {
                        return $message->to($user->email)
                            ->subject($subject);
                    }
                );

                //Update the sent column.
                if (!count($this->mailer->failures())) {
                    //Update sent column
                    if (!empty($notification)) {
                        $this->emailNotifications->updateSentTime($notification->id);
                    }

                    return true;
                }
            } catch (\Exception $e) {
                Log::error("Sending email failed: " . $e->getMessage());
                return false;
            }
        }
    }
}
