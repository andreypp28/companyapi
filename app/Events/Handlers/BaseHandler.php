<?php

namespace WA\Events\Handlers;

use Illuminate\Events\Dispatcher;

/**
 * Class BaseHandler.
 */
abstract class BaseHandler
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        // No main subscriptions yet, should be declared in children
    }
}
