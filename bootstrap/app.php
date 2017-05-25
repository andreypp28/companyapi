<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$app->withFacades();

/**
Set up Logging with Papertrail
**/
$app->configureMonologUsing(function($monolog) {
    $syslog = new \Monolog\Handler\SyslogHandler('lumen');
    $formatter = new \Monolog\Formatter\LineFormatter(null, null, false, true);
    $syslog->setFormatter($formatter);
    $monolog->pushHandler($syslog);
    return $monolog;
});

$app->withEloquent();

config([
    "filesystems" => [
        'default' => 'local',
        'disks' => [
            'local' => [
                'driver' => 'local',
                'root' => storage_path('app/public'),
            ],
        ],
    ],
]);

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    WA\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    WA\Console\Kernel::class
);


$app->singleton('filesystem', function ($app) {
    return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
});


/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    \WA\Http\Middleware\CorsMiddleware::class
]);

$app->routeMiddleware([
    'auth' => WA\Http\Middleware\Authenticate::class,
    'role' => 'Zizaco\Entrust\Middleware\EntrustRole',
    'permission' => 'Zizaco\Entrust\Middleware\EntrustPermission',
    'ability' => 'Zizaco\Entrust\Middleware\EntrustAbility',
    'oauth-user-instance' => \WA\Http\Middleware\OAuth2UserInstance::class,
    'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
    'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class
]);

/*
|--------------------------------------------------------------------------
| Register Configurations
|--------------------------------------------------------------------------
|
*/
$app->configure('api');
$app->configure('cache');
$app->configure('services');
$app->configure('mail');
$app->configure('saml2_settings');
$app->configure('entrust');


/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(WA\Providers\AppServiceProvider::class);
// $app->register(WA\Providers\EventServiceProvider::class);
$app->register(\Culpa\CulpaServiceProvider::class);
$app->register(\Dingo\Api\Provider\LumenServiceProvider::class);
$app->register(\WA\Providers\RepositoriesServiceProviders::class);
$app->register(\WA\Providers\AppServiceProvider::class);
$app->register(\Illuminate\Auth\Passwords\PasswordResetServiceProvider::class);
$app->register(\Illuminate\Mail\MailServiceProvider::class);
$app->register(\WA\Providers\EventServiceProvider::class);
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);
$app->register(\WA\Providers\Saml2ServiceProvider::class);
$app->register(\WA\Providers\CatchAllOptionsRequestsProvider::class);
$app->register(\GrahamCampbell\Flysystem\FlysystemServiceProvider::class);
$app->register(WA\Providers\AuthServiceProvider::class);
$app->register(Dusterio\LumenPassport\PassportServiceProvider::class);
$app->register(\WA\Providers\SSOGrantProvider::class);
app('Dingo\Api\Transformer\Factory')->setAdapter(function ($app) {
    $base_url = env('API_DOMAIN', 'api.wirelessanalytics.com');
    // $serializer = new \League\Fractal\Serializer\JsonApiSerializer($base_url);
    $serializer = new \WA\Http\Responses\JsonApiSerializer($base_url);
    $fractal = new League\Fractal\Manager;
    $fractal->setSerializer($serializer);
    return new Dingo\Api\Transformer\Adapter\Fractal($fractal, 'include', ',');
});

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/


$app->group(['namespace' => 'WA\Http\Controllers'], function ($app) {
    require __DIR__ . '/../app/Http/routes.php';
});

/*
|--------------------------------------------------------------------------
| Aliases
|--------------------------------------------------------------------------
|
*/

if (!class_exists('Response')) {
    class_alias('Illuminate\Support\Facades\Response', 'Response');
}

if (!class_exists('Config')) {
    class_alias('Illuminate\Support\Facades\Config', 'Config');
}

if (!class_exists('Authorizer')) {
   
}

if (!class_exists('Uuid')) {
    class_alias(\Webpatser\Uuid\Uuid::class, 'Uuid');
}

if (!class_exists('Saml2')) {
    class_alias(\WA\Auth\Saml2\Saml2AuthFacade::class, 'Saml2');
}

if (!class_exists('Request')) {
    class_alias('Illuminate\Support\Facades\Request', 'Request');
}


if (!class_exists('Flysystem')) {
    class_alias('\GrahamCampbell\Flysystem\Facades\Flysystem', 'Flysystem');
}


return $app;
