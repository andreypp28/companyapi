<?php

namespace WA\Auth;

use League\OAuth2\Server\Grant\PasswordGrant as PassGrant;
use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Event;
use League\OAuth2\Server\Exception;
use League\OAuth2\Server\Util\SecureKey;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use WA\DataStore\Scope\Scope;
use DB;
use WA\DataStore\User\User;
use WA\DataStore\Permission\Permission;
use WA\DataStore\Role\Role;

use Log;

/**
 * Password grant class.
 */
class PasswordGrant extends PassGrant
{
        /**
     * {@inheritdoc}
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    ) {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));
        $user = $this->validateUser($request, $client);

        if (!$this->thisUserHasTheCorrectScope($scopes, $user->getIdentifier())) {
            $error['errors']['scopes'] = 'The User has not assigned the scope needed to complete the request.';
            return response()->json($error)->setStatusCode('401');
        }

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

    public function thisUserHasTheCorrectScope($scopes, $userId){
       //ROLES of the USER Retrived From DB
        $user = User::find($userId);

        $roles = $user->roles;
        
        $perms = array();
        foreach ($scopes as $scp) {
           //SCOPES requested by Name         
            $scope = Scope::getByName($scp->getIdentifier())[0];
           // PERMISSIONS of the Scope
            $scopePerms = $scope->permissions;
            //PUSH the name of the Permissions into the Array
            foreach ($scopePerms as $perm) {
                array_push($perms, $perm->name);                            
            }
        }

        // CHECK IF THE ROLES HAS THE PERMISSIONS
        if($user->ability($roles, $perms)){
            
            return true;
        }
        return false;

    }
}
