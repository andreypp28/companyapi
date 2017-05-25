<?php

namespace WA\Auth;

use League\OAuth2\Server\ResponseTypes\BearerTokenResponse as LeagueBearerTokenResponse;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

class BearerTokenResponse extends LeagueBearerTokenResponse
{
    /**
     * Add custom fields to your Bearer Token response here, then override
     * AuthorizationServer::getResponseType() to pull in your version of
     * this class rather than the default.
     *
     * @param AccessTokenEntityInterface $accessToken
     *
     * @return array
     */
    protected function getExtraParams(AccessTokenEntityInterface $accessToken)
    {
        return ['user_id' => $accessToken->getUserIdentifier()];
    }
}
