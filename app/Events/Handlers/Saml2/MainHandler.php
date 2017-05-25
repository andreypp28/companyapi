<?php

/**
 * MainHandler - Gets the event received by the Single Sign On.
 *
 * @author   Agustí Dosaiguas
 */

namespace WA\Events\Handlers\Saml2;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use WA\DataStore\CarrierDestinationMap;
use WA\DataStore\Company\CompanySaml2;
use WA\DataStore\User\User;

use WA\Events\Handlers\BaseHandler;
use WA\Events\PodcastWasPurchased;

use Auth;
use Cache;
use Carbon\Carbon;
use Log;

/**
 * Class MainHandler.
 */
class MainHandler extends BaseHandler
{
    /**
     * @param $data
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function saml2LoginUser($event)
    {
        // Get the User Data Info from the Saml2 User.
        $userData = $this->getUserDataFromSaml2User($event);
        Log::debug("MainHandler@saml2LoginUser - userData: " . print_r(json_encode($userData), true));

        // Get the information of the company from company_saml2 table using the entityId.
        $companySaml = $this->getCompanySamlFromRequest();
        Log::debug("MainHandler@saml2LoginUser - companySaml: " . print_r($companySaml, true));

        // Request the information of the user using the requested data from the event and the attributes retrieved from the company_saml2 table.
        $infoUser = $this->parseRequestedInfoFromIdp($userData, $companySaml);
        Log::debug("MainHandler@saml2LoginUser - infoUser: " . print_r($infoUser, true));

        // Get IDP user Email from User Data Info
        if ($infoUser['email'] != '') {
            $laravelUser = User::where('email', $infoUser['email'])->first();
            if (!isset($laravelUser)) {
                // CREATE USER
                $userInterface = app()->make('WA\Repositories\User\UserInterface');
                $laravelUser = $userInterface->create(
                    array(
                        'email' => $infoUser['email'],
                        'firstName' => $infoUser['firstName'],
                        'lastName' => $infoUser['lastName'],
                        'isActive' => 1,
                        'companyId' => $companySaml['companyId']
                        )
                    );
            }

            // Get the UUID from url.
            $uuid = $this->getUuidFromRequestRelayState();
            Cache::put('saml2user_'.$uuid, $laravelUser, 15);
        }
        return true;
    }

    /**
     * @param Dispatcher $events
     */
    public function handle(Dispatcher $events)
    {
        $events->listen('Aacotroneo\Saml2\Events\Saml2LoginEvent', 'WA\Events\Handlers\Saml2\MainHandler@saml2LoginUser');
    }

    /*
     * @getUserDataFromSaml2User
     * @event: the requested event that allow to retrieve the information of the User.
     *
     * @return: the user id, attributes and the saml assertion raw.
     *
     */
    private function getUserDataFromSaml2User($event)
    {
        // Get Saml2 User from $Event.
        $user = $event->getSaml2User();

        // Get the User Data Info from the Saml2 User.
        return [
            'id' => $user->getUserId(),
            'attributes' => $user->getAttributes(),
            'assertion' => $user->getRawSamlAssertion()
        ];
    }

    /*
     * @getCompanySamlFromRequest
     *
     * @return: the companysaml2 information retrieved using the entityId from the request.
     *
     */
    private function getCompanySamlFromRequest() {
        $samlResponse = base64_decode(app('request')->get('SAMLResponse'));
        $xml = new \SimpleXMLElement($samlResponse);
        $entityIdNode = $xml->xpath("/*[local-name()='Response']/*[local-name()='Issuer']");
        $entityId = $entityIdNode[0]->__toString();

        return CompanySaml2::where('entityId', $entityId)->first();
    }

    /*
     * @parseRequestedInfoFromIdp
     * @userData: the user information retrieved using the getUserDataFromSaml2User() function.
     * @companySaml: the companysaml2 information retrieved using the getCompanySamlFromRequest() function.
     *
     * @return: the user information from the attributes.
     *
     */
    private function parseRequestedInfoFromIdp($userData, $companySaml)
    {
        //return array('email' => 'dev@testsaml.com', 'firstName' => 'Sirion', 'lastName' => 'Developers', 'isActive' => 1, 'companyId' => 9,); // TESTING

        return array(
            'email' => isset($userData['attributes'][$companySaml['emailAttribute']]) ? 
                $userData['attributes'][$companySaml['emailAttribute']][0] : '',
            'firstName' => isset($userData['attributes'][$companySaml['firstNameAttribute']]) ? 
                $userData['attributes'][$companySaml['firstNameAttribute']][0] : '',
            'lastName' => isset($userData['attributes'][$companySaml['lastNameAttribute']]) ? 
                $userData['attributes'][$companySaml['lastNameAttribute']][0] : ''
        );
    }

    /*
     * @getUuidFromRequestRelayState
     *
     * @return: the uuid retrieved from the url.
     *
     */
    private function getUuidFromRequestRelayState()
    {
        $relayState = app('request')->input('RelayState');
        $path_parts = explode('/', $relayState);

        return $path_parts[count($path_parts) - 1];
    }
}
