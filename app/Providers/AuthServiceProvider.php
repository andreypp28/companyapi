<?php

namespace WA\Providers;

use Laravel\Passport\PassportServiceProvider;
use WA\Auth\BearerTokenResponse;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\Passport;
use WA\DataStore\Scope\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
//use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use WA\Auth\PasswordGrant;
use Log;
use Schema;
use Carbon\Carbon;

class AuthServiceProvider extends PassportServiceProvider
{
    /**
     * Make the authorization service instance.
     *
     * @return AuthorizationServer
     */
    public function makeAuthorizationServer()
    {
        return new AuthorizationServer(
            $this->app->make(\Laravel\Passport\Bridge\ClientRepository::class),
            $this->app->make(\Laravel\Passport\Bridge\AccessTokenRepository::class),
            $this->app->make(\Laravel\Passport\Bridge\ScopeRepository::class),
            'file://'.\Laravel\Passport\Passport::keyPath('oauth-private.key'),
            'file://'.\Laravel\Passport\Passport::keyPath('oauth-public.key'),
            new BearerTokenResponse()
        );
    }

      public function boot()
    {

        parent::boot();
            //$this->registerPolicies();

            //Passport::routes();
        if (Schema::hasTable('scopes')){

            $scope = Scope::all();
            
            $listScope = array();
            foreach ($scope as $scop){
                $listScope[$scop->getAttributes()['name']] = $scop->getAttributes()['description'];
            }

            Passport::tokensCan($listScope);      
        }

        $aux = 6;
        if (env('TOKEN_EXPIRES_IN') != null) { $aux = env('TOKEN_EXPIRES_IN'); }
        Passport::tokensExpireIn(Carbon::now()->addDays($aux));
        //Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }

    /**
     * Create and configure a Password grant instance.
     *
     * @return PasswordGrant
     */
    /*protected function makePasswordGrant()
    {
        $grant = new PasswordGrant(
            $this->app->make(\Laravel\Passport\Bridge\UserRepository::class),
            $this->app->make(\Laravel\Passport\Bridge\RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }*/
}
