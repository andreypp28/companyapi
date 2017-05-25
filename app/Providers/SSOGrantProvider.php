<?php
namespace WA\Providers;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\ScopeRepository;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2Server\Grant\PasswordGrant;
use Log;
/**
 * Class CustomQueueServiceProvider
 *
 * @package App\Providers
 */
class SSOGrantProvider extends PassportServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //$ClientInterface = app()->make('WA\vendor\league\oauth2-server\Repositories\ClientRepositoryInterface');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function register()
    {
             
      
        app(AuthorizationServer::class)->enableGrantType($this->makeSSOGrant(), Passport::tokensExpireIn());

       
    }
    /**
     * Create and configure a Password grant instance.
     *
     * @return PasswordGrant
     */

    protected function makeSSOGrant()
    { 
        $grant = new \WA\Auth\SSOGrant(
          
            $this->app->make(UserRepository::class),
            $this->app->make(RefreshTokenRepository::class)
        );
        
        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        return $grant;
    }
}