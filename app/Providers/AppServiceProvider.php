<?php

namespace WA\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use WA\DataStore\Company\Company;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
    }

    public function boot()
    {
        Relation::morphMap([
            'company' => Company::class,
        ]);
    }
}
