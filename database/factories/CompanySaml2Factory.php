<?php


$factory->define(\WA\DataStore\Company\CompanySaml2::class, function ($faker) {
    return [
        'entityId' => 'https://sts.windows.net/d6f6af8c-1dd5-4480-afe3-53aaa025a273/',
        'singleSignOnServiceUrl' => 'https://login.microsoftonline.com/d6f6af8c-1dd5-4480-afe3-53aaa025a273/saml2',
        'singleSignOnServiceBinding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        'singleLogoutServiceUrl' => 'https://login.microsoftonline.com/d6f6af8c-1dd5-4480-afe3-53aaa025a273/saml2',
        'singleLogoutServiceBinding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        'x509cert' => 'MIIC4jCCAcqgAwIBAgIQfQ29fkGSsb1J8n2KueDFtDANBgkqhkiG9w0BAQsFADAtMSswKQYDVQQDEyJhY2NvdW50cy5hY2Nlc3Njb250cm9sLndpbmRvd3MubmV0MB4XDTE2MDQxNzAwMDAwMFoXDTE4MDQxNzAwMDAwMFowLTErMCkGA1UEAxMiYWNjb3VudHMuYWNjZXNzY29udHJvbC53aW5kb3dzLm5ldDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAL23Ba49fdxpus3qOXtv8ueCcePbCIEnL/tiTvp+jcTakGilZzJB3M/ktY9hX6RZ4KLcBjM2SClQmNUivEimTBX0U1N8L06GSE8H91tUKup/ofmm6qciU2qiHH4QNHepBADOTbEACoX78O363tUInJlPS1lVlGAGsi5okV+qN7ZLSauh+fKVM07cfw9A6a58es+bFvrojIqS1264GJjns+4baJCVYA4PMPsgxQsWTaOylbnlJC5MYTY2BpBn57dfLO2VtN+lqE5nWkJluAgoX/6OEyxOVchqWFpuyP/p1feQQb8Jc6JFVSs73in95eVFN3Oj5BsvgQdxPwoahZurD1sCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAe5RxtMLU2i4/vN1YacncR3GkOlbRv82rll9cd5mtVmokAw7kwbFBFNo2vIVkun+n+VdJf+QRzmHGm3ABtKwz3DPr78y0qdVFA3h9P60hd3wqu2k5/Q8s9j1Kq3u9TIEoHlGJqNzjqO7khX6VcJ6BRLzoefBYavqoDSgJ3mkkYCNqTV2ZxDNks3obPg4yUkh5flULH14TqlFIOhXbsd775aPuMT+/tyqcc6xohU5NyYA63KtWG1BLDuF4LEF84oNPcY9i0n6IphEGgz20H7YcLRNjU55pDbWGdjE4X8ANb23kAc75RZn9EY4qYCiqeIAg3qEVKLnLUx0fNKMHmuedjg==',
        'companyId' => 1,
        'emailAttribute' => '',
        'firstNameAttribute' => '',
        'lastNameAttribute' => '',
    ];
});
