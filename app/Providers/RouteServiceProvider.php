<?php

//namespace WA\Providers;
namespace App\Providers; 

use Laravel\Passport\Passport; 
use Illuminate\Support\Facades\Route; 
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'WA\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     */
    /*public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
        });
    }*/
     public function map() { 
        $this->mapWebRoutes();
        $this->mapApiRoutes();
        Passport::routes();
    }
}
