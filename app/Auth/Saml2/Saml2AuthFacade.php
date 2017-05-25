<?php

/**
 * Saml2AuthFacade - Extends Saml2A and modifies getFacadeAccessor function.
 *
 * @author   Agustí Dosaiguas
 */

namespace WA\Auth\Saml2;

use Aacotroneo\Saml2\Facades\Saml2Auth as Saml2A;

class Saml2AuthFacade extends Saml2A
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'WA\Auth\Saml2\Saml2Auth';
    }
}
