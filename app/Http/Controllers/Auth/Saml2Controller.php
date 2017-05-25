<?php

/**
 * Saml2Controller - Extends Saml2C and modifies acs function.
 *
 * @author   Agustí Dosaiguas
 */

namespace WA\Http\Controllers\Auth;

use Aacotroneo\Saml2\Http\Controllers\Saml2Controller as Saml2C;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use WA\Auth\Saml2\Saml2Auth;
use Illuminate\Http\Request;
use Event;

class Saml2Controller extends Saml2C
{
    /**
     * @param Saml2Auth $saml2Auth injected
     */
    public function __construct(Saml2Auth $saml2Auth)
    {
        $this->saml2Auth = $saml2Auth;
    }

    public function acs()
    {
        $errors = $this->saml2Auth->acs();

        if (!empty($errors)) {
            return $errors;
        }

        $user = $this->saml2Auth->getSaml2User();
        event(new Saml2LoginEvent($user));

        $relayState = app('request')->input('RelayState');
        $url = app('Illuminate\Routing\UrlGenerator');
        if ($relayState && $url->full() != $relayState) {
            return redirect($relayState);
        } else {
            return redirect(config('saml2_settings.loginRoute'));
        }
    }
}
