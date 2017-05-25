<?php
/**
 * OAuth 2.0 SSO grant.
 *
 * @author      Agustí Dosaiguas
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace WA\Auth;

//namespace WA\Passport;

use Illuminate\Http\Request;
use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Log;
/**
 * SSO grant class.
 */
class SSOGrant extends AbstractGrant
{
/**
     * @param UserRepositoryInterface         $userRepository
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository
    )
    {
        $this->setUserRepository($userRepository);
        $this->setRefreshTokenRepository($refreshTokenRepository);
        $this->refreshTokenTTL = new \DateInterval('P1M');
    }
    /**
     * {@inheritdoc}
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    )
    {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));
        $user = $this->validateUser($request);
        // Finalize the requested scopes
        $scopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());
        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $scopes);
        $refreshToken = $this->issueRefreshToken($accessToken);
        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);
        return $responseType;
    }
    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'sso';
    }
    /**
     * @param ServerRequestInterface $request
     *
     * @return UserEntityInterface
     * @throws OAuthServerException
     */

    
    protected function validateUser(ServerRequestInterface $request)
    {

        $laravelRequest = new Request($request->getParsedBody());
        $user = $this->getUserEntityByRequest($laravelRequest);
        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidCredentials();
        }
        return $user;
    }
    /**
     * Retrieve user by request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Laravel\Passport\Bridge\User|null
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    protected function getUserEntityByRequest(Request $request)
    {
        if (is_null($model = config('auth.providers.users.model'))) { 
            throw OAuthServerException::serverError('Unable to determine user model from configuration.');
        }
        if (method_exists($model, 'byPassportSSOGrantRequest')) { 
            $user = (new $model)->byPassportSSOGrantRequest($request);
        } else { 
            throw OAuthServerException::serverError('Unable to find byPassportSSOGrantRequest method on user model.');
        }
        return ($user) ? new User($user->id) : null;
    }
}



    /**
     * Grant identifier.
     *
     * @var string
     
    protected $identifier = 'sso';

    /**
     * Response type.
     *
     * @var string
     
    protected $responseType;

    /**
     * Callback to authenticate a user's name and password.
     *
     * @var callable
     
    protected $callback;

    /**
     * Access token expires in override.
     *
     * @var int
     
    protected $accessTokenTTL;

    /**
     * Set the callback to verify a user's username and password.
     *
     * @param callable $callback The callback function
     
    public function setVerifyCredentialsCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Return the callback function.
     *
     * @return callable
     *
     * @throws
     
    protected function getVerifyCredentialsCallback()
    {
        if (is_null($this->callback) || !is_callable($this->callback)) {
            throw new Exception\ServerErrorException('Null or non-callable callback set on Password grant');
        }

        return $this->callback;
    }

    /**
     * Complete the password grant.
     *
     * @return array
     *
     * @throws
     
    public function completeFlow()
    {
        // Get the required params
        $clientId = $this->server->getRequest()->request->get('client_id', $this->server->getRequest()->getUser());
        if (is_null($clientId)) {
            throw new Exception\InvalidRequestException('client_id');
        }

        $clientSecret = $this->server->getRequest()->request->get('client_secret',
            $this->server->getRequest()->getPassword());
        if (is_null($clientSecret)) {
            throw new Exception\InvalidRequestException('client_secret');
        }

        // Validate client ID and client secret
        $client = $this->server->getClientStorage()->get(
            $clientId,
            $clientSecret,
            null,
            $this->getIdentifier()
        );

        if (($client instanceof ClientEntity) === false) {
            $this->server->getEventEmitter()->emit(new Event\ClientAuthenticationFailedEvent($this->server->getRequest()));
            throw new Exception\InvalidClientException();
        }

        // Get the UUID from url.
        $uuid = app('request')->input('uuid');

        // Check if user's username and password are correct
        // IF FAILS CHECK storage/framwork/cache permissions.
        $userId = call_user_func($this->getVerifyCredentialsCallback(), $uuid);

        if ($userId === false) {
            $this->server->getEventEmitter()->emit(new Event\UserAuthenticationFailedEvent($this->server->getRequest()));
            throw new Exception\InvalidCredentialsException();
        }

        // Validate any scopes that are in the request
        $scopeParam = $this->server->getRequest()->request->get('scope', '');
        $scopes = $this->validateScopes($scopeParam, $client);

        // Create a new session
        $session = new SessionEntity($this->server);
        $session->setOwner('user', $userId);
        $session->associateClient($client);

        // Generate an access token
        $accessToken = new AccessTokenEntity($this->server);
        $accessToken->setId(SecureKey::generate());
        $accessToken->setExpireTime($this->getAccessTokenTTL() + time());

        // Associate scopes with the session and access token
        foreach ($scopes as $scope) {
            $session->associateScope($scope);
        }

        foreach ($session->getScopes() as $scope) {
            $accessToken->associateScope($scope);
        }

        $this->server->getTokenType()->setSession($session);
        $this->server->getTokenType()->setParam('access_token', $accessToken->getId());
        $this->server->getTokenType()->setParam('expires_in', $this->getAccessTokenTTL());

        // Associate a refresh token if set
        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken = new RefreshTokenEntity($this->server);
            $refreshToken->setId(SecureKey::generate());
            $refreshToken->setExpireTime($this->server->getGrantType('refresh_token')->getRefreshTokenTTL() + time());
            $this->server->getTokenType()->setParam('refresh_token', $refreshToken->getId());
        }

        // Save everything
        $session->save();
        $accessToken->setSession($session);
        $accessToken->save();

        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken->setAccessToken($accessToken);
            $refreshToken->save();
        }

        $response = $this->server->getTokenType()->generateResponse();
        $response['user_id'] = $userId;

        return $response;
    }



    
}*/
