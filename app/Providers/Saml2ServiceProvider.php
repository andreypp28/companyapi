<?php

/**
 * Saml2ServiceProvider - Extends Saml2SP and modifies Saml2_settings on the fly.
 *
 * @author   Agustí Dosaiguas
 */

namespace WA\Providers;

use Aacotroneo\Saml2\Saml2ServiceProvider as Saml2SP;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use WA\DataStore\Company\CompanySaml2;

use Cache;
use OneLogin_Saml2_Auth;
use Log;
use Request;
use Session;
use URL;

class Saml2ServiceProvider extends Saml2SP
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        if (config('saml2_settings.useRoutes', false) == true) {
            $this->app->group(
                [
                    'prefix' => config('saml2_settings.routesPrefix'),
                    'middleware' => config('saml2_settings.routesMiddleware'),
                ],
                function ($app) {
                    $routeSaml2Controller = 'WA\Http\Controllers\Auth\Saml2Controller';
                    $app->get('logout', ['as' => 'saml_logout', function () use ($routeSaml2Controller, $app) {
                        $controller = $app->make($routeSaml2Controller);
                        return $controller->logout();
                    }]);

                    $app->get('login', ['as' => 'saml_login', function () use ($routeSaml2Controller, $app) {
                        $controller = $app->make($routeSaml2Controller);
                        return $controller->login();
                    }]);

                    $app->get('metadata', ['as' => 'saml_metadata', function () use ($routeSaml2Controller, $app) {
                        $controller = $app->make($routeSaml2Controller);
                        return $controller->metadata();
                    }]);

                    $app->post('acs', ['as' => 'saml_acs', function () use ($routeSaml2Controller, $app) {
                        $controller = $app->make($routeSaml2Controller);
                        return $controller->acs();
                    }]);

                    $app->get('sls', ['as' => 'saml_sls', function () use ($routeSaml2Controller, $app) {
                        $controller = $app->make($routeSaml2Controller);
                        return $controller->sls();
                    }]);
                }
            );
            //include __DIR__ . '/../../routes.php';
        }

        $this->publishes([
            __DIR__.'/../../config/saml2_settings.php' => config_path('saml2_settings.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('WA\Auth\Saml2\Saml2Auth', function ($app) {
            $config = config('saml2_settings');
            $method = Request::getMethod();
            $pathInfo = Request::getPathInfo();

            if (stripos($pathInfo, 'doSSO') > 0) {
                $parts = explode('/', $pathInfo);
                $email = $parts[count($parts)-1];
                $idCompany = Cache::get('saml2_idcompany_'.$email);
            } else {
                $samlResponse = base64_decode(app('request')->get('SAMLResponse'));

                if($samlResponse != '' || $samlResponse != null) {
                    $xml = new \SimpleXMLElement($samlResponse);
                    $entityIdNode = $xml->xpath("/*[local-name()='Response']/*[local-name()='Issuer']");
                    $entityId = $entityIdNode[0]->__toString();

                    $companySaml = CompanySaml2::where('entityId', $entityId)->first();
                } else {
                    $companySaml = CompanySaml2::first();
                }

                $idCompany = $companySaml['companyId'];
            }

            // Load Saml2Settings from IdCompany.
            $company = app()->make('WA\Repositories\Company\CompanyInterface');
            $companyByID = $company->byId($idCompany);
            if (!isset($companyByID)) {
                abort(404);
            }

            $saml2Settings = $companyByID->saml2Settings();

            // Route information
            $config['sp']['entityId'] = URL::route('saml_metadata');
            $config['sp']['assertionConsumerService']['url'] = URL::route('saml_acs');
            $config['sp']['singleLogoutService']['url'] = URL::route('saml_sls');

            // Saml2_Settings Information.
            $config['idp']['entityId'] = $saml2Settings['attributes']['entityId'];
            $config['idp']['singleSignOnService']['url'] = $saml2Settings['attributes']['singleSignOnServiceUrl'];
            $config['idp']['singleSignOnService']['binding'] = $saml2Settings['attributes']['singleSignOnServiceBinding'];
            $config['idp']['singleLogoutService']['url'] = $saml2Settings['attributes']['singleLogoutServiceUrl'];
            $config['idp']['singleLogoutService']['binding'] = $saml2Settings['attributes']['singleLogoutServiceBinding'];
            $config['idp']['x509cert'] = $saml2Settings['attributes']['x509cert'];

            $auth = new OneLogin_Saml2_Auth($config);

            return new \WA\Auth\Saml2\Saml2Auth($auth);
        });
    }
}
