<?php

namespace WA\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'WA\Events\SomeEvent' => [
            'WA\Listeners\EventListener',
        ],
        'Aacotroneo\Saml2\Events\Saml2LoginEvent' => [
            'WA\Events\Handlers\Saml2\MainHandler@saml2LoginUser',
        ],
    ];

/*
    public function register(){
        Event::subscribe('WA\Events\Handlers\Saml2\MainHandler');
    }
*/
}
