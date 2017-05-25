<?php
/**
 * saml2_settings - Saml2 Settings File.
 *  
 * @author   Agustí Dosaiguas
 */

//This is variable is an example - Just make sure that the urls in the 'idp' config are ok.
$idp_host = 'http://simplesamlphp.dev/simplesaml';

return $settings = array(
    /*****
     * Cosmetic settings - controller routes
     **/
    'useRoutes' => true, //include library routes and controllers


    'routesPrefix' => '/saml2',

    /**
     * which middleware group to use for the saml routes
     * Laravel 5.2 will need a group which includes StartSession
     */
    'routesMiddleware' => [],

    /**
     * Where to redirect after logout
     */
    'logoutRoute' => '/login',
    
    /**
     * Where to redirect after login if no other option was provided
     */
    'loginRoute' => '/dashboard',

    /**
     * Where to redirect after login if no other option was provided
     */
    'errorRoute' => '/ssoError',




    /*****
     * One Login Settings
     */



    // If 'strict' is True, then the PHP Toolkit will reject unsigned
    // or unencrypted messages if it expects them signed or encrypted
    // Also will reject the messages if not strictly follow the SAML
    // standard: Destination, NameId, Conditions ... are validated too.
    'strict' => true, //@todo: make this depend on laravel config

    // Enable debug mode (to print errors)
    'debug' => false, //@todo: make this depend on laravel config

    // Service Provider Data that we are deploying
    'sp' => array(

        // Specifies constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        'x509cert' => '',
        'privateKey' => '',

        //LARAVEL - You don't need to change anything else on the sp
        // Identifier of the SP entity  (must be a URI)
        'entityId' => '', //LARAVEL: This would be set to saml_metadata route
        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => array(
            // URL Location where the <Response> from the IdP will be returned
            'url' => '', //LARAVEL: This would be set to saml_acs route
            // SAML protocol binding to be used when returning the <Response>
            // message.  Onelogin Toolkit supports for this endpoint the
            // HTTP-Redirect binding only
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
        ),
        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        'singleLogoutService' => array(
            // URL Location where the <Response> from the IdP will be returned
            'url' => '', //LARAVEL: This would be set to saml_sls route
            // SAML protocol binding to be used when returning the <Response>
            // message.  Onelogin Toolkit supports for this endpoint the
            // HTTP-Redirect binding only
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
    ),

    // Identity Provider Data that we want connect with our SP
    'idp' => array(
        // Identifier of the IdP entity  (must be a URI)
        //'entityId' => $idp_host . '/saml2/idp/metadata.php',
        'entityId' => '',
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => array(
            // URL Target of the IdP where the SP will send the Authentication Request Message
            //'url' => $idp_host . '/saml2/idp/SSOService.php',
            'url' => '',
            // SAML protocol binding to be used when returning the <Response>
            // message.  Onelogin Toolkit supports for this endpoint the
            // HTTP-POST binding only
            //'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'binding' => '',
        ),
        // SLO endpoint info of the IdP.
        'singleLogoutService' => array(
            // URL Location of the IdP where the SP will send the SLO Request
            //'url' => $idp_host . '/saml2/idp/SingleLogoutService.php',
            'url' => '',
            // SAML protocol binding to be used when returning the <Response>
            // message.  Onelogin Toolkit supports for this endpoint the
            // HTTP-Redirect binding only
            //'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'binding' => '',
        ),
        // Public x509 certificate of the IdP
        //'x509cert' => 'MIIDXTCCAkWgAwIBAgIJAMtuJVQu8b68MA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNVBAYTAkFVMRMwEQYDVQQIDApTb21lLVN0YXRlMSEwHwYDVQQKDBhJbnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQwHhcNMTYwNzA1MDkwODM1WhcNMjYwNzA1MDkwODM1WjBFMQswCQYDVQQGEwJBVTETMBEGA1UECAwKU29tZS1TdGF0ZTEhMB8GA1UECgwYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsPLmLoR69FYGOobPcQ8tmcBLwh1iPkL4QSZ8IisReUUENf9JfHRB8ie8+NQdsMJM9oqZ1OXTtHSgHfOk0bnC2r8qtx0SYbFVoZXw9YQofO6bxZBYWqWfJd8ADXo0fj47ywCVAwMhOBkSJy8NVK5rwXoo7DVOLeRS4Qu0mvsz8IK0wujUmoe+LNs7YEIbnsyDeSM6eqHgVuU9JQGhO20SMas+EdzU/E+o0PLaZlIo4CNpiC8DhbAIV+mLNxoVb7QHteNPDJEBRndURfaKiH8SKH2zuMN0Ay0AZUQzbYl/DZgm0WDyFfwqFhwBBg8sDMB4sV/vYSCbV2qd4CcFjyGtUQIDAQABo1AwTjAdBgNVHQ4EFgQUh6QbF4rPbOU+qVxjSRubWIk2jEswHwYDVR0jBBgwFoAUh6QbF4rPbOU+qVxjSRubWIk2jEswDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAIhX1+HSWLulEBKImMMels+GKNSIH7/RqOVvP9lE1DTaKJtjx9ZxbrqSIvSA1j6aiCOqMr0ok63Eg3kSSaPDxlaD9xVVIEGTTwZ01pvev5pbGzhpW2M0s6nNi0AGz8aCZGotugxLX7p4qPuuhnr5vnYu/MqhIEQrXhQsTizzQKfu6W4aojlRZNuiJEKJDALavu2l1Iby4eLUubFuBWAsPHpbIB+5QtYjjUVlGTnXKCrUubBj3GVIhdKtzPG10uyHNgT2wBcdj7T9tdPHT0XzkIjndHvhOJKqXDBcqYphXnrWlfqpLYUD3xwlHIbSohYSaiNRbcjaPwk6OWs/O/SBTPw==',
        'x509cert' => '',
        /*
         *  Instead of use the whole x509cert you can use a fingerprint
         *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it)
         */
        // 'certFingerprint' => '',
    ),



    /***
     *
     *  OneLogin advanced settings
     *
     *
     */
    // Security settings
    'security' => array(

        /** signatures and encryptions offered */

        // Indicates that the nameID of the <samlp:logoutRequest> sent by this SP
        // will be encrypted.
        'nameIdEncrypted' => false,

        // Indicates whether the <samlp:AuthnRequest> messages sent by this SP
        // will be signed.              [The Metadata of the SP will offer this info]
        'authnRequestsSigned' => false,

        // Indicates whether the <samlp:logoutRequest> messages sent by this SP
        // will be signed.
        'logoutRequestSigned' => false,

        // Indicates whether the <samlp:logoutResponse> messages sent by this SP
        // will be signed.
        'logoutResponseSigned' => false,

        /* Sign the Metadata
         False || True (use sp certs) || array (
                                                    keyFileName => 'metadata.key',
                                                    certFileName => 'metadata.crt'
                                                )
        */
        'signMetadata' => false,


        /** signatures and encryptions required **/

        // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
        // <samlp:LogoutResponse> elements received by this SP to be signed.
        'wantMessagesSigned' => false,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be signed.        [The Metadata of the SP will offer this info]
        'wantAssertionsSigned' => false,

        // Indicates a requirement for the NameID received by
        // this SP to be encrypted.
        'wantNameIdEncrypted' => false,

        // Authentication context.
        // Set to false and no AuthContext will be sent in the AuthNRequest,
        // Set true or don't present thi parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'
        // Set an array with the possible auth context values: array ('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509'),
        'requestedAuthnContext' => false,
    ),

    // Contact information template, it is recommended to suply a technical and support contacts
    'contactPerson' => array(
        'technical' => array(
            'givenName' => 'Technical Support',
            'emailAddress' => 'devsupport@wirelessanalytics.com'
        ),
        'support' => array(
            'givenName' => 'Support',
            'emailAddress' => 'support@wirelessanalytics.com'
        ),
    ),

    // Organization information template, the info in en_US lang is recomended, add more if required
    'organization' => array(
        'en-US' => array(
            'name' => 'Wireless Analytics, LLC',
            'displayname' => 'Wireless Analytics',
            'url' => 'http://www.wirelessanalytics.com'
        ),
    ),

/* Interoperable SAML 2.0 Web Browser SSO Profile [saml2int]   http://saml2int.org/profile/current

   'authnRequestsSigned' => false,    // SP SHOULD NOT sign the <samlp:AuthnRequest>,
                                      // MUST NOT assume that the IdP validates the sign
   'wantAssertionsSigned' => true,
   'wantAssertionsEncrypted' => true, // MUST be enabled if SSL/HTTPs is disabled
   'wantNameIdEncrypted' => false,
*/

);