<?php

namespace WA\Services\Notification\Exception;

use Illuminate\Mail\Mailer as Mailer;
use WA\Services\Notification\NotifierInterface;

/**
 * Class MailNotifier.
 */
class MailNotifier implements NotifierInterface
{
    /**
     * @var \Illuminate\Mail\Mailer
     */
    protected $mailer;

    /**
     * @var
     */
    protected $from = 'dev@wirelessanalytics.com';

    /**
     * @var
     */
    protected $to = 'dev@wirelessanalytics.com';

    /**
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send the notification.
     *
     * @param $subject
     * @param $message
     *
     * @return mixed
     */
    public function notify($subject, $message)
    {
        $data = $message; // Laravel has issues with that var name
        $this->mailer->send(
            'emails.errors.exception',
            $data,
            function ($msg) use ($subject, $message) {
                $msg->to($this->to, 'Dev')->subject($subject);
            }
        );
    }
}
