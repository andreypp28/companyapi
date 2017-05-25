<?php

/**
 * Saml2Auth - Extends Saml2A and modifies login function.
 *
 * @author   Agustí Dosaiguas
 */

namespace WA\Auth\Saml2;

use Aacotroneo\Saml2\Saml2Auth as Saml2A;
use OneLogin_Saml2_Response;
use DOMDocument;

class Saml2Auth extends Saml2A
{
    /**
     * @var \OneLogin_Saml2_Auth
     */

    /**
     * Initiate a saml2 login flow. It will redirect! Before calling this, check if user is
     * authenticated (here in saml2). That would be true when the assertion was received this request.
     */
    public function login($returnTo = null)
    {
        $auth = $this->auth;
        $parameters = array();

        $forceAuthn = false;
        $isPassive = false;
        $stay = true;  //If $stay is True, it return a string with the SLO URL + LogoutRequest + parameters
        $setNameIdPolicy = true;

        return $auth->login($returnTo, $parameters, $forceAuthn, $isPassive, $stay, $setNameIdPolicy);
    }

    /**
     * Process a Saml response (assertion consumer service)
     * When errors are encountered, it returns an array with proper description.
     */
    public function acs()
    {
        $auth = $this->auth;

        $auth->processResponse();

        $errors['errors'] = $auth->getErrors();
        $errors['errorReason'] = $auth->getLastErrorReason();

        if (!empty($errors['errors'] || !empty($errors['errorReason']))) {
            $response = new OneLogin_Saml2_Response($auth->getSettings(), $_POST['SAMLResponse']);

            $title = '<h2>Your request has given the next error, please, contact administrator.</h2>';
            $error = '<h3 style="color:red">'.$errors['errors'][0].' - '.$errors['errorReason'].'</h3>';

            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            /* @var $xml SimpleXMLElement */
            $domxml->loadXML($response->response);
            $result = $domxml->saveXML();

            return $title.$error.'<pre>'.htmlspecialchars($result).'</pre>';
        }

        if (!$auth->isAuthenticated()) {
            return array('error' => 'Could not authenticate');
        }

        return null;
    }
}
