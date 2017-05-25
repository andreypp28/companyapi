<?php

namespace WA\Services\Notification\Exception;

use Illuminate\Log\Writer as Logger;
use WA\Services\Notification\NotifierInterface;

/**
 * Class LogNotifier.
 */
class LogNotifier implements NotifierInterface
{
    /**
     * @var \Illuminate\Log\Writer
     */
    protected $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
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
        $this->logger->useErrorLog(
            'debug',
            $subject.' '.$message
        );
    }
}
