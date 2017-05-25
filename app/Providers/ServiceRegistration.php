<?php

namespace WA\Providers;

use WA\DataStore\Address\Address;
use WA\DataStore\Allocation\Allocation;
use WA\DataStore\App\App;
use WA\DataStore\Asset\Asset;
use WA\DataStore\Attribute;
use WA\DataStore\Carrier\Carrier;
use WA\DataStore\Category\CategoryApp;
use WA\DataStore\Preset\Preset;
use WA\DataStore\Company\Company;
use WA\DataStore\Company\CompanyCurrentBillMonth;
use WA\DataStore\Condition\Condition;
use WA\DataStore\Condition\ConditionField;
use WA\DataStore\Condition\ConditionOperator;
use WA\DataStore\Content\Content;
use WA\DataStore\Device\Device;
use WA\DataStore\Device\DeviceCarrier;
use WA\DataStore\Device\DeviceCompany;
use WA\DataStore\Device\DeviceImage;
use WA\DataStore\Device\DeviceModification;
use WA\DataStore\DeviceType\DeviceType;
use WA\DataStore\EmailNotifications;
use WA\DataStore\Image\Image;
use WA\DataStore\JobStatus;
use WA\DataStore\Location\Location;
use WA\DataStore\Modification\Modification;
use WA\DataStore\NotificationCategory;
use WA\DataStore\Order\Order;
use WA\DataStore\Package\Package;
use WA\DataStore\DeviceVariation\DeviceVariation;
use WA\DataStore\Request\Request;
use WA\DataStore\Role\Role;
use WA\DataStore\Permission\Permission;
use WA\DataStore\Scope\Scope;
use WA\DataStore\Service\Service;
use WA\DataStore\ServiceItem\ServiceItem;
use WA\DataStore\Udl\Udl;
use WA\DataStore\UdlValue\UdlValue;
use WA\DataStore\UdlValuePath\UdlValuePath;
use WA\DataStore\UdlValuePathUsers\UdlValuePathUsers;
use WA\DataStore\User\User;
use WA\DataStore\UserNotifications;
use WA\Repositories\Address\EloquentAddress;
use WA\Repositories\Allocation\EloquentAllocation;
use WA\Repositories\App\EloquentApp;
use WA\Repositories\Asset\EloquentAsset;
use WA\Repositories\Attribute\EloquentAttribute;
use WA\Repositories\Carrier\EloquentCarrier;
use WA\Repositories\Category\EloquentCategoryApps;
use WA\Repositories\Category\EloquentPreset;
use WA\Repositories\Company\EloquentCompany;
use WA\Repositories\CompanyCurrentBillMonth\EloquentCompanyCurrentBillMonth;
use WA\Repositories\Condition\EloquentCondition;
use WA\Repositories\Condition\EloquentConditionField;
use WA\Repositories\Condition\EloquentConditionOperator;
use WA\Repositories\Content\EloquentContent;
use WA\Repositories\Device\EloquentDevice;
use WA\Repositories\Device\EloquentDeviceCarrier;
use WA\Repositories\Device\EloquentDeviceCompany;
use WA\Repositories\Device\EloquentDeviceImage;
use WA\Repositories\Device\EloquentDeviceModification;
use WA\Repositories\DeviceType\EloquentDeviceType;
use WA\Repositories\EmailNotifications\EloquentEmailNotifications;
use WA\Repositories\Image\EloquentImage;
use WA\Repositories\JobStatus\EloquentJobStatus;
use WA\Repositories\Location\EloquentLocation;
use WA\Repositories\Modification\EloquentModification;
use WA\Repositories\NotificationCategory\EloquentNotificationCategory;
use WA\Repositories\Order\EloquentOrder;
use WA\Repositories\Package\EloquentPackage;
use WA\Repositories\Scope\EloquentScope;
use WA\Repositories\Permission\EloquentPermission;
use WA\Repositories\DeviceVariation\EloquentDeviceVariation;
use WA\Repositories\Request\EloquentRequest;
use WA\Repositories\Role\EloquentRole;
use WA\Repositories\Service\EloquentService;
use WA\Repositories\ServiceItem\EloquentServiceItem;
use WA\Repositories\Udl\EloquentUdl;
use WA\Repositories\UdlValue\EloquentUdlValue;
use WA\Repositories\UdlValuePath\EloquentUdlValuePath;
use WA\Repositories\UdlValuePathUsers\EloquentUdlValuePathUsers;
use WA\Repositories\User\EloquentUser;
use WA\Repositories\User\UserCacheDecorator;
use WA\Services\Cache\Cache;

/**
 * Class ServiceRegistration.
 */
trait ServiceRegistration
{
    /**
     * @param
     */
    public function registerDevice()
    {
        app()->singleton(
            'WA\Repositories\Device\DeviceInterface',
            function () {
                return new EloquentDevice(new Device(),
                    app()->make('WA\Repositories\JobStatus\JobStatusInterface')
                );
            }
        );
    }

    /**
     * @param
     */
    public function registerImage()
    {
        app()->singleton(
            'WA\Repositories\Image\ImageInterface',
            function () {
                return new EloquentImage(new Image(),
                    app()->make('WA\Repositories\JobStatus\JobStatusInterface')
                );
            }
        );
    }

    /**
     * @param
     */
    public function registerDeviceModification()
    {
        app()->singleton(
            'WA\Repositories\Device\DeviceModificationInterface',
            function () {
                return new EloquentDeviceModification(new DeviceModification(),
                    app()->make('WA\Repositories\JobStatus\JobStatusInterface')
                );
            }
        );
    }

    /**
     * @param
     */
    public function registerDeviceImage()
    {
        app()->singleton(
            'WA\Repositories\Device\DeviceImageInterface',
            function () {
                return new EloquentDeviceImage(new DeviceImage(),
                    app()->make('WA\Repositories\JobStatus\JobStatusInterface')
                );
            }
        );
    }

    /**
     * @param
     */
    public function registerDeviceCarrier()
    {
        app()->singleton(
            'WA\Repositories\Device\DeviceCarrierInterface',
            function () {
                return new EloquentDeviceCarrier(new DeviceCarrier(),
                    app()->make('WA\Repositories\JobStatus\JobStatusInterface')
                );
            }
        );
    }

    /**
     * @param
     */
    public function registerDeviceCompany()
    {
        app()->singleton(
            'WA\Repositories\Device\DeviceCompanyInterface',
            function () {
                return new EloquentDeviceCompany(new DeviceCompany(),
                    app()->make('WA\Repositories\JobStatus\JobStatusInterface')
                );
            }
        );
    }

    /**
     * @param
     */
    public function registerDeviceVariation()
    {
        app()->singleton(
            'WA\Repositories\DeviceVariation\DeviceVariationInterface',
            function () {
                return new EloquentDeviceVariation(new DeviceVariation(),
                    app()->make('WA\Repositories\JobStatus\JobStatusInterface')
                );
            }
        );
    }

    /**
     * @param
     */
    public function registerJobStatus()
    {
        app()->bind(
            'WA\Repositories\JobStatus\JobStatusInterface',
            function () {
                return new EloquentJobStatus(new JobStatus());
            }
        );
    }

    /**
     * @param
     */
    public function registerUdl()
    {
        app()->bind(
            'WA\Repositories\Udl\UdlInterface',
            function () {
                return new EloquentUdl(new Udl(), app()->make('WA\Repositories\UdlValue\UdlValueInterface'));
            }
        );
    }

    /**
     * @param
     */
    public function registerUdlValue()
    {
        app()->bind(
            'WA\Repositories\UdlValue\UdlValueInterface',
            function () {
                return new EloquentUdlValue(new UdlValue());
            }
        );
    }

    /**
     * @param
     */
    public function registerUdlValuePath()
    {
        app()->bind(
            'WA\Repositories\UdlValuePath\UdlValuePathInterface',
            function () {
                return new EloquentUdlValuePath(new UdlValuePath());
            }
        );
    }

    /**
     * @param
     */
    public function registerUdlValuePathUsers()
    {
        app()->bind(
            'WA\Repositories\UdlValuePathUsers\UdlValuePathUsersInterface',
            function () {
                return new EloquentUdlValuePathUsers(new UdlValuePathUsers());
            }
        );
    }

    /**
     * @param
     */
    protected function registerCompany()
    {
        app()->bind(
            'WA\Repositories\Company\CompanyInterface',
            function () {
                return new EloquentCompany(
                    new Company(),
                    app()->make('WA\Repositories\User\UserInterface'),
                    app()->make('WA\Repositories\Udl\UdlInterface'),
                    app()->make('WA\Repositories\Carrier\CarrierInterface'),
                    app()->make('WA\Repositories\Device\DeviceInterface')
                );
            }
        );
    }

    /**
     * @param
     */
    protected function registerUser()
    {
        app()->bind(
            'WA\Repositories\User\UserInterface',
            function () {
                $user = new EloquentUser(
                    new User(),
                    app()->make('WA\Repositories\UdlValue\UdlValueInterface'),
                    app()->make('WA\Repositories\Udl\UdlInterface')
                );
                //return $user

                return new UserCacheDecorator(
                    $user,
                    new Cache(app()['cache'], 'users', 10)
                );
            }
        );
    }

    /**
     * @param
     */
    protected function registerAsset()
    {
        app()->singleton(
            'WA\Repositories\Asset\AssetInterface',
            function () {
                return new EloquentAsset(new Asset(),
                    app()->make('WA\Repositories\JobStatus\JobStatusInterface'),
                    app()->make('WA\Repositories\User\UserInterface'),
                    app()->make('WA\Repositories\Carrier\CarrierInterface'));
            }
        );
    }

    /**
     * @param
     */
    protected function registerAttribute()
    {
        app()->bind(
            'WA\Repositories\Attribute\AttributeInterface',
            function () {
                return new EloquentAttribute(new Attribute());
            }
        );
    }

    /**
     * @param
     */
    protected function registerDeviceType()
    {
        app()->bind('WA\Repositories\DeviceType\DeviceTypeInterface',
            function () {
                return new EloquentDeviceType(new DeviceType());
            });
    }

    /**
     * @param
     */
    protected function registerCarrier()
    {
        app()->bind('WA\Repositories\Carrier\CarrierInterface',
            function () {
                return new EloquentCarrier(new Carrier());
            });
    }

    /**
     * @param
     */
    public function registerLocation()
    {
        app()->bind('WA\Repositories\Location\LocationInterface', function () {
            return new EloquentLocation(new Location());
        });
    }

    /**
     * @param
     */
    protected function registerNotificationCategory()
    {
        app()->bind('WA\Repositories\NotificationCategory\NotificationCategoryInterface',
            function () {
                return new EloquentNotificationCategory(new NotificationCategory());
            });
    }

    /**
     * @param
     */
    public function registerUserNotification()
    {
        app()->bind('WA\Repositories\UserNotifications\UserNotificationsInterface',
            function () {
                return new EloquentUserNotifications(new UserNotifications());
            });
    }

    public function registerEmailNotifications()
    {
        app()->bind('WA\Repositories\EmailNotifications\EmailNotificationsInterface',
            function () {
                return new EloquentEmailNotifications(new EmailNotifications());
            });
    }

    public function registerScope()
    {
        app()->bind('WA\Repositories\Scope\ScopeInterface',
            function () {
                return new EloquentScope( new Scope(),
                    app()->make('WA\Repositories\Permission\PermissionInterface'));
            });
    }
    public function registerPermission()
    {
        app()->bind('WA\Repositories\Permission\PermissionInterface',
            function () {
                return new EloquentPermission(new Permission(),
                    app()->make('WA\Repositories\Role\RoleInterface'));
            });
    }
    public function registerRole()
    {
        app()->bind('WA\Repositories\Role\RoleInterface',
            function () {
                return new EloquentRole(new Role());
            });
    }


    public function registerAllocation()
    {
        app()->bind('WA\Repositories\Allocation\AllocationInterface',
            function () {
                return new EloquentAllocation(new Allocation());
            });
    }

    public function registerContent()
    {
        app()->bind('WA\Repositories\Content\ContentInterface',
            function () {
                return new EloquentContent(new Content());
            });
    }

    public function registerService()
    {
        app()->bind('WA\Repositories\Service\ServiceInterface',
            function () {
                return new EloquentService(new Service());
            }
        );
    }

    public function registerApp()
    {
        app()->bind('WA\Repositories\App\AppInterface',
            function () {
                return new EloquentApp(new App());
            }
        );
    }

    public function registerOrder()
    {
        app()->bind('WA\Repositories\Order\OrderInterface',
            function () {
                return new EloquentOrder(new Order());
            }
        );
    }

    public function registerPackage()
    {
        app()->bind('WA\Repositories\Package\PackageInterface',
            function () {
                return new EloquentPackage(new Package());
            }
        );
    }

    public function registerAddress()
    {
        app()->bind('WA\Repositories\Address\AddressInterface',
            function () {
                return new EloquentAddress(new Address());
            }
        );
    }

    public function registerRequest()
    {
        app()->bind('WA\Repositories\Request\RequestInterface',
            function () {
                return new EloquentRequest(new Request());
            }
        );
    }

    public function registerModification()
    {
        app()->bind('WA\Repositories\Modification\ModificationInterface',
            function () {
                return new EloquentModification(new Modification());
            }
        );
    }

    /**
     * @param
     */
    public function registerPreset()
    {
        app()->bind('WA\Repositories\Category\PresetInterface',
            function () {
                return new EloquentPreset(new Preset());
            }
        );
    }

    /**
     * @param
     */
    public function registerCategoryApp()
    {
        app()->bind('WA\Repositories\Category\CategoryAppsInterface',
            function () {
                return new EloquentCategoryApps(new CategoryApp());
            }
        );
    }

    /**
     * @param
     */
    public function registerCondition()
    {
        app()->bind('WA\Repositories\Condition\ConditionInterface',
            function () {
                return new EloquentCondition(new Condition());
            }
        );
    }

    /**
     * @param
     */
    public function registerConditionField()
    {
        app()->bind('WA\Repositories\Condition\ConditionFieldInterface',
            function () {
                return new EloquentConditionField(new ConditionField());
            }
        );
    }

    /**
     * @param
     */
    public function registerConditionOperator()
    {
        app()->bind('WA\Repositories\Condition\ConditionOperatorInterface',
            function () {
                return new EloquentConditionOperator(new ConditionOperator());
            }
        );
    }

    /**
     * @param
     */
    public function registerServiceItem()
    {
        app()->bind('WA\Repositories\ServiceItem\ServiceItemInterface',
            function () {
                return new EloquentServiceItem(new ServiceItem());
            }
        );
    }

    public function registerCurrentBillMonth()
    {

        app()->bind('WA\Repositories\CompanyCurrentBillMonth\CompanyCurrentBillMonthInterface',
            function () {
                return new EloquentCompanyCurrentBillMonth(new CompanyCurrentBillMonth());
            });
    }
}
