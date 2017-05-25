<?php

namespace WA\Services\Notification;

use Illuminate\Support\ServiceProvider;
use WA\Services\Notification\Exception\LogNotifier;

/**
 * Class NotificationServiceProvider.
 */
class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $app = $this->app;

        $app['wa.notifier'] = $app->share(
            function ($app) {
                $notifier = new LogNotifier($app['log']);

                return $notifier;
            }
        );
    }
}
