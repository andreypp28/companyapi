<?php

namespace wa\auth\saml2;

use TestCase;
use OneLogin_Saml2_Auth;

class Saml2AuthTest extends TestCase
{ 

    public function testLogin()
    {
        // Route information
        $config['sp']['entityId'] = 'http://clean.local/saml2/metadata';

        $config['sp']['assertionConsumerService']['url'] = 'http://clean.local/saml2/acs';

        $config['sp']['singleLogoutService']['url'] = 'http://clean.local/saml2/sls';

        // Saml2_Settings Information.
        $config['idp']['entityId'] =
                  'https://sts.windows.net/d6f6af8c-1dd5-4480-afe3-53aaa025a273/';

        $config['idp']['singleSignOnService']['url'] =
                  'https://login.microsoftonline.com/d6f6af8c-1dd5-4480-afe3-53aaa025a273/saml2';

        $config['idp']['singleSignOnService']['binding'] =
                  'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect';

        $config['idp']['singleLogoutService']['url'] =
                  'https://login.microsoftonline.com/d6f6af8c-1dd5-4480-afe3-53aaa025a273/saml2';

        $config['idp']['singleLogoutService']['binding'] =
                  'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect';

        $config['idp']['x509cert'] =
                  'MIIC4jCCAcqgAwIBAgIQQNXrmzhLN4VGlUXDYCRT3zANBgkqhkiG9w0BAQsFADAtMSswKQYDVQQDEyJhY2NvdW50cy5hY2Nlc3Njb250cm9sLndpbmRvd3MubmV0MB4XDTE0MTAyODAwMDAwMFoXDTE2MTAyNzAwMDAwMFowLTErMCkGA1UEAxMiYWNjb3VudHMuYWNjZXNzY29udHJvbC53aW5kb3dzLm5ldDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALyKs/uPhEf7zVizjfcr/ISGFe9+yUOqwpel38zgutvLHmFD39E2hpPdQhcXn4c4dt1fU5KvkbcDdVbP8+e4TvNpJMy/nEB2V92zCQ/hhBjilwhF1ETe1TMmVjALs0KFvbxW9ZN3EdUVvxFvz/gvG29nQhl4QWKj3x8opr89lmq14Z7T0mzOV8kub+cgsOU/1bsKqrIqN1fMKKFhjKaetctdjYTfGzVQ0AJAzzbtg0/Q1wdYNAnhSDafygEv6kNiquk0r0RyasUUevEXs2LY3vSgKsKseI8ZZlQEMtE9/k/iAG7JNcEbVg53YTurNTrPnXJOU88mf3TToX14HpYsS1ECAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAfolx45w0i8CdAUjjeAaYdhG9+NDHxop0UvNOqlGqYJexqPLuvX8iyUaYxNGzZxFgGI3GpKfmQP2JQWQ1E5JtY/n8iNLOKRMwqkuxSCKJxZJq4Sl/m/Yv7TS1P5LNgAj8QLCypxsWrTAmq2HSpkeSk4JBtsYxX6uhbGM/K1sEktKybVTHu22/7TmRqWTmOUy9wQvMjJb2IXdMGLG3hVntN/WWcs5w8vbt1i8Kk6o19W2MjZ95JaECKjBDYRlhG1KmSBtrsKsCBQoBzwH/rXfksTO9JoUYLXiW0IppB7DhNH4PJ5hZI91R8rR0H3/bKkLSuDaKLWSqMhozdhXsIIKvJQ==';

        $auth = new OneLogin_Saml2_Auth($config);
        $Saml2Auth = new \WA\Auth\Saml2\Saml2Auth($auth);
        $returnTo = '07b67a80-6090-11e6-a3a9-cbf6af43dd14';
        $return = $Saml2Auth->login($returnTo);
        $this->assertStringStartsWith('https://login.microsoftonline.com/', $return);
        //$return = 'https://login.microsoftonline.com/d6f6af8c-1dd5-4480-afe3-53aaa025a273/saml2?SAMLRequest=fVPbjtowEH3nK1DeQ27cYgEVhV6QKCBI%2B9CXatYe71py7NR2uuzf10nYLl3t4hdLM3POnDkezyyUsiLL2j2oI%2F6u0bpe359zKZUlbXIe1EYRDVZYoqBESxwlp%2BW3LUkHMamMdppqGbyC3UaBtWic0KqDbdbzYL%2F7tN1%2F2ex%2BxWwyTfCO8hSSPBvG45zHQ55PRjHlNE2GHDnNR2nWQX%2BgsZ5nHnjaoNexWVvjRlkHyvl4nIzDeBomkyKekjQjaf6zg679sEKBa%2BEPzlWWRJHU90INSkGNtpo7raRQOKC6jNiYj4FPaZgwNgqHw2kcAscsHGUAEKcjSCdZ1IyedvSHizMfhWJC3d825K4rsuRrURzCw%2F5UdCTLZ6NWWtm6RHNC80dQ%2FH7cdpq9ZCoR1EBqCrLrHwG1HwRb6bIC9TTPg0XLNWuSpHXHLN7FluiAgYNrgll0DX0hq8jOT7JZH7QU9KmNN%2BezNiW49wdOBkkbESzkbSmpla2QCi6QBf9ollLqx5VBcDgPnKkx6Ef%2FNb8sLLJ2fb1DDs%2Bu36o2wjavimeg7jL%2BiwXX5Svpd%2FGIfHFzXSmhTZ0PH%2Fz1qA1rXhep710Y8OK1cReT3iTvVEc3ZC96z%2Bnrv7j4Cw%3D%3D&RelayState=07b67a80-6090-11e6-a3a9-cbf6af43dd14';
    }
}
