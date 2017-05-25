<?php

namespace WA\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Register all the service providers for the DataStore Repositories
 * which are independent of the underlying persistent layer.
 *
 * Class RepositoriesServiceProviders
 */
class RepositoriesServiceProviders extends ServiceProvider
{
    use ServiceRegistration;

    /**
     * Register all Providers.
     */
    public function register()
    {
        $this->registerServiceItem();
        $this->registerApp();
        $this->registerAsset();
        $this->registerDevice();
        $this->registerCarrier();
        
        $this->registerDeviceCarrier();
        $this->registerCompany();
        $this->registerDeviceCompany();
        $this->registerImage();
        $this->registerDeviceImage();
        $this->registerModification();
        $this->registerDeviceModification();
        $this->registerDeviceVariation();
        $this->registerDeviceType();
        $this->registerUser();
        $this->registerJobStatus();
        $this->registerUdl();
        $this->registerUdlValue();
        $this->registerAttribute();

        $this->registerLocation();
        $this->registerUdlValuePath();
        $this->registerUdlValuePathUsers();
        $this->registerNotificationCategory();
        $this->registerUserNotification();
        $this->registerEmailNotifications();
        $this->registerRole();
        $this->registerPermission();
        $this->registerScope();
        $this->registerAllocation();
        $this->registerContent();
        $this->registerService();
        $this->registerOrder();
        $this->registerPackage();
        $this->registerAddress();
        $this->registerRequest();
        $this->registerPreset();
        $this->registerCategoryApp();
        $this->registerCondition();
        $this->registerConditionField();
        $this->registerConditionOperator();
        $this->registerCurrentBillMonth();
    }
}
