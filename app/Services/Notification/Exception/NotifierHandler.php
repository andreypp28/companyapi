<?php

namespace WA\Services\Notification\Exception;

use WA\Exceptions\HandlerInterface;
use WA\Exceptions\WAException;
use WA\Services\Notification\NotifierInterface;

/**
 * Class NotifierHandler.
 */
class NotifierHandler implements HandlerInterface
{
    /**
     * @var NotifierInterface
     */
    protected $notifier;

    /**
     * @param NotifierInterface $notifier
     */
    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @param WAException $exception
     *
     * @return mixed
     */
    public function handle(WAException $exception)
    {
        $this->sendException($exception);
    }

    /**
     * @param \Exception $e
     */
    protected function sendException(\Exception $e)
    {
        $this->notifier->notify('Error: '.get_class($e), $e->getMessage());
    }
}
