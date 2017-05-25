<?php

/**
 * CompanySaml2TableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class CompanySaml2TableSeeder extends BaseTableSeeder
{
    protected $table = 'company_saml2';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $dataMicrosoft = [
            'entityId' => 'https://sts.windows.net/d6f6af8c-1dd5-4480-afe3-53aaa025a273/',
            'singleSignOnServiceUrl' => 'https://login.microsoftonline.com/d6f6af8c-1dd5-4480-afe3-53aaa025a273/saml2',
            'singleSignOnServiceBinding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'singleLogoutServiceUrl' => 'https://login.microsoftonline.com/d6f6af8c-1dd5-4480-afe3-53aaa025a273/saml2',
            'singleLogoutServiceBinding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'x509cert' => 'MIIDBTCCAe2gAwIBAgIQPLxWKJ0EEqNLJ1eIGhsS/jANBgkqhkiG9w0BAQsFADAtMSswKQYDVQQDEyJhY2NvdW50cy5hY2Nlc3Njb250cm9sLndpbmRvd3MubmV0MB4XDTE2MDkwNTAwMDAwMFoXDTE4MDkwNjAwMDAwMFowLTErMCkGA1UEAxMiYWNjb3VudHMuYWNjZXNzY29udHJvbC53aW5kb3dzLm5ldDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKEXDfH9+M8nNOH9ZfhuIn3+UrkdUBNuOac3fHhKViTpufqgBl4EG8acO3iBJWMHhuX9PS/4Agik20fNG/7BnAdr7/0b0XduOhzmwrVIweLQ5JkuifrrdS0cF3WiJ6E99Vsm1eYYaTcH4xk5GNg2/sDhzT03xzXiPha74McLS50VgbqoBIh2sKVcC7E5/GFe5HYwENdC/UdI+89HwmSadeFO5Qxrua+VYk9WhtMUkOEjo8rdBZB06zOYpxA/Wn9Sx1RYjOrCBLvlrnXvvJtAqlhaCqWOSXT+/QWT5AetcvLBSi2t2kWpyj8/Qc1vnqez3vfW6qAyzTaA6TbSb+8jlzMCAwEAAaMhMB8wHQYDVR0OBBYEFJuS8ySZ1mYXPa4Sq1nSrl1G41rXMA0GCSqGSIb3DQEBCwUAA4IBAQBxf5BldsfSq05AAnco9NlToMPsXf46GbInCC/o2R+4WbwJ3uzZe+2/o86nI5gFcq/hGy/HXZXdsWj6py6fI0T5Av0GlhCxAuCmsMoyEMmoGdEnSL6cMfAA57lsAgDGVOB3OdzZoK3um1fpb0paXv1eColOIYsL9lY91Bk4P3E496IDAbkjCjiFzsiQerlmzXSHhvSjvas2g6VTQEwj8/9l4xZO1O3BhExdZHWAkUW1ZciTSB4Ite5bcAHWWBRqMUB7Da5Yj674SocHFhGM+9iM6xaJfMSYjlDFB2rNDSUv8ZLIyDpHB9Ry9N8p7znyixhpiWn0nPVqfX84LMckrgfs',
            'companyId' => 9,
            'emailAttribute' => '',
            'firstNameAttribute' => '',
            'lastNameAttribute' => '',
        ];

        $dataSimpleSamlPhp = [
            'entityId' => 'http://simplesamlphp.dev/simplesaml/saml2/idp/metadata.php',
            'singleSignOnServiceUrl' => 'http://simplesamlphp.dev/simplesaml/saml2/idp/SSOService.php',
            'singleSignOnServiceBinding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'singleLogoutServiceUrl' => 'http://simplesamlphp.dev/simplesaml/saml2/idp/SingleLogoutService.php',
            'singleLogoutServiceBinding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'x509cert' => 'MIIDXTCCAkWgAwIBAgIJAMtuJVQu8b68MA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNVBAYTAkFVMRMwEQYDVQQIDApTb21lLVN0YXRlMSEwHwYDVQQKDBhJbnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQwHhcNMTYwNzA1MDkwODM1WhcNMjYwNzA1MDkwODM1WjBFMQswCQYDVQQGEwJBVTETMBEGA1UECAwKU29tZS1TdGF0ZTEhMB8GA1UECgwYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsPLmLoR69FYGOobPcQ8tmcBLwh1iPkL4QSZ8IisReUUENf9JfHRB8ie8+NQdsMJM9oqZ1OXTtHSgHfOk0bnC2r8qtx0SYbFVoZXw9YQofO6bxZBYWqWfJd8ADXo0fj47ywCVAwMhOBkSJy8NVK5rwXoo7DVOLeRS4Qu0mvsz8IK0wujUmoe+LNs7YEIbnsyDeSM6eqHgVuU9JQGhO20SMas+EdzU/E+o0PLaZlIo4CNpiC8DhbAIV+mLNxoVb7QHteNPDJEBRndURfaKiH8SKH2zuMN0Ay0AZUQzbYl/DZgm0WDyFfwqFhwBBg8sDMB4sV/vYSCbV2qd4CcFjyGtUQIDAQABo1AwTjAdBgNVHQ4EFgQUh6QbF4rPbOU+qVxjSRubWIk2jEswHwYDVR0jBBgwFoAUh6QbF4rPbOU+qVxjSRubWIk2jEswDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAIhX1+HSWLulEBKImMMels+GKNSIH7/RqOVvP9lE1DTaKJtjx9ZxbrqSIvSA1j6aiCOqMr0ok63Eg3kSSaPDxlaD9xVVIEGTTwZ01pvev5pbGzhpW2M0s6nNi0AGz8aCZGotugxLX7p4qPuuhnr5vnYu/MqhIEQrXhQsTizzQKfu6W4aojlRZNuiJEKJDALavu2l1Iby4eLUubFuBWAsPHpbIB+5QtYjjUVlGTnXKCrUubBj3GVIhdKtzPG10uyHNgT2wBcdj7T9tdPHT0XzkIjndHvhOJKqXDBcqYphXnrWlfqpLYUD3xwlHIbSohYSaiNRbcjaPwk6OWs/O/SBTPw==',
            'companyId' => 2,
            'emailAttribute' => 'facebook_user',
            'firstNameAttribute' => 'facebook.name',
            'lastNameAttribute' => 'facebook.name',
        ];

        $this->loadTable($dataMicrosoft);
        $this->loadTable($dataSimpleSamlPhp);
    }
}
