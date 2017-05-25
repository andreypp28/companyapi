<?php

/**
 * PermissionTableSeeder - Insert info into database.
 *
 * @author   Marcio de Rezende
 */
class PermissionsTableSeeder extends BaseTableSeeder
{
    protected $table = 'permissions';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

            //roleS
        $dataRoles = [
             [
                'name' => 'get_roles',
                'display_name' => 'View all roles',
                'description' => 'View all roles',
                
            ],
            [
                'name' => 'get_role',
                'display_name' => 'View a role',
                'description' => 'View a role',
            ],
            [
                'name' => 'create_role',
                'display_name' => 'Create a role',
                'description' => 'Create a role',
                
            ],
            [
                'name' => 'update_role',
                'display_name' => 'Update a role',
                'description' => 'Update a role',
                
            ],
            [
                'name' => 'delete_role',
                'display_name' => 'Erase a role',
                'description' => 'Erase a role',
                
            ],
        ];
            //permissionS
        $dataPermissions = [
             [
                'name' => 'get_permissions',
                'display_name' => 'View all permissions',
                'description' => 'View all permissions',
                
            ],
            [
                'name' => 'get_permission',
                'display_name' => 'View a permission',
                'description' => 'View a permission',
            ],
            [
                'name' => 'create_permission',
                'display_name' => 'Create a permission',
                'description' => 'Create a permission',
                
            ],
            [
                'name' => 'update_permission',
                'display_name' => 'Update a permission',
                'description' => 'Update a permission',
                
            ],
            [
                'name' => 'delete_permission',
                'display_name' => 'Erase a permission',
                'description' => 'Erase a permission',
                
            ],
        ];
            //scopeS
        $dataScopes = [
             [
                'name' => 'get_scopes',
                'display_name' => 'View all scopes',
                'description' => 'View all scopes',
                
            ],
            [
                'name' => 'get_scope',
                'display_name' => 'View a scope',
                'description' => 'View a scope',
            ],
            [
                'name' => 'create_scope',
                'display_name' => 'Create a scope',
                'description' => 'Create a scope',
                
            ],
            [
                'name' => 'update_scope',
                'display_name' => 'Update a scope',
                'description' => 'Update a scope',
                
            ],
            [
                'name' => 'delete_scope',
                'display_name' => 'Erase a scope',
                'description' => 'Erase a scope',
                
            ],
        ];

        //COMPANIES
        $dataCompanies = [

            [
                'name' => 'get_companies',
                'display_name' => 'View all companies',
                'description' => 'View all companies',
                
            ],
            [
                'name' => 'get_company',
                'display_name' => 'View a company',
                'description' => 'View a company',
            ],
            [
                'name' => 'create_company',
                'display_name' => 'Create a company',
                'description' => 'Create a company',
                
            ],
            [
                'name' => 'update_company',
                'display_name' => 'Update a company',
                'description' => 'Update a company',
                
            ],
            [
                'name' => 'delete_company',
                'display_name' => 'Erase a company',
                'description' => 'Erase a company',
                
            ],
        ];
            //USER
        $dataUser = [
             [
                'name' => 'get_users',
                'display_name' => 'View all users',
                'description' => 'View all users',
                
            ],
            [
                'name' => 'get_user',
                'display_name' => 'View a user',
                'description' => 'View a user',
            ],
            [
                'name' => 'create_user',
                'display_name' => 'Create a user',
                'description' => 'Create a user',
                
            ],
        ];
            //ASSETS
        $dataAssets = [
             [
                'name' => 'get_assetss',
                'display_name' => 'View all assetss',
                'description' => 'View all assetss',
                
            ],
            [
                'name' => 'get_assets',
                'display_name' => 'View a assets',
                'description' => 'View a assets',
            ],
        ];
            //DEVICES
        $dataDevices = [
             [
                'name' => 'get_devices',
                'display_name' => 'View all devices',
                'description' => 'View all devices',
                
            ],
            [
                'name' => 'get_device',
                'display_name' => 'View a device',
                'description' => 'View a device',
            ],
            [
                'name' => 'create_device',
                'display_name' => 'Create a device',
                'description' => 'Create a device',
                
            ],
            [
                'name' => 'update_device',
                'display_name' => 'Update a device',
                'description' => 'Update a device',
                
            ],
            [
                'name' => 'delete_device',
                'display_name' => 'Erase a device',
                'description' => 'Erase a device',
                
            ],
        ];
            //CONTENTS
        $dataContents = [
             [
                'name' => 'get_contents',
                'display_name' => 'View all contents',
                'description' => 'View all contents',
                
            ],
            [
                'name' => 'get_content',
                'display_name' => 'View a content',
                'description' => 'View a content',
            ],
            [
                'name' => 'create_content',
                'display_name' => 'Create a content',
                'description' => 'Create a content',
                
            ],
            [
                'name' => 'update_content',
                'display_name' => 'Update a content',
                'description' => 'Update a content',
                
            ],
            [
                'name' => 'delete_content',
                'display_name' => 'Erase a content',
                'description' => 'Erase a content',
                
            ],
        ];    
            //APP
        $dataApp = [
             [
                'name' => 'get_apps',
                'display_name' => 'View all apps',
                'description' => 'View all apps',
                
            ],
            [
                'name' => 'get_app',
                'display_name' => 'View a app',
                'description' => 'View a app',
            ],
            [
                'name' => 'create_app',
                'display_name' => 'Create a app',
                'description' => 'Create a app',
                
            ],
            [
                'name' => 'update_app',
                'display_name' => 'Update a app',
                'description' => 'Update a app',
                
            ],
            [
                'name' => 'delete_app',
                'display_name' => 'Erase a app',
                'description' => 'Erase a app',
                
            ],
        ];
            //ORDER
        $dataOrder = [
             [
                'name' => 'get_orders',
                'display_name' => 'View all orders',
                'description' => 'View all orders',
                
            ],
            [
                'name' => 'get_order',
                'display_name' => 'View a order',
                'description' => 'View a order',
            ],
            [
                'name' => 'create_order',
                'display_name' => 'Create a order',
                'description' => 'Create a order',
                
            ],
            [
                'name' => 'update_order',
                'display_name' => 'Update a order',
                'description' => 'Update a order',
                
            ],
            [
                'name' => 'delete_order',
                'display_name' => 'Erase a order',
                'description' => 'Erase a order',
                
            ],
        ];
            //REQUEST
        $dataRequest = [
             [
                'name' => 'get_requests',
                'display_name' => 'View all requests',
                'description' => 'View all requests',
                
            ],
            [
                'name' => 'get_request',
                'display_name' => 'View a request',
                'description' => 'View a request',
            ],
            [
                'name' => 'create_request',
                'display_name' => 'Create a request',
                'description' => 'Create a request',
                
            ],
            [
                'name' => 'update_request',
                'display_name' => 'Update a request',
                'description' => 'Update a request',
                
            ],
            [
                'name' => 'delete_request',
                'display_name' => 'Erase a request',
                'description' => 'Erase a request',
                
            ],
        ];
            //PACKAGE
        $dataPackage = [
             [
                'name' => 'get_packages',
                'display_name' => 'View all packages',
                'description' => 'View all packages',
                
            ],
            [
                'name' => 'get_package',
                'display_name' => 'View a package',
                'description' => 'View a package',
            ],
            [
                'name' => 'get_package_forUser',
                'display_name' => 'User view a package',
                'description' => 'User view a package',
            ],
            [
                'name' => 'create_package',
                'display_name' => 'Create a package',
                'description' => 'Create a package',
                
            ],
            [
                'name' => 'update_package',
                'display_name' => 'Update a package',
                'description' => 'Update a package',
                
            ],
            [
                'name' => 'delete_package',
                'display_name' => 'Erase a package',
                'description' => 'Erase a package',
                
            ],
        ];
            //SERVICE
        $dataService = [
             [
                'name' => 'get_services',
                'display_name' => 'View all services',
                'description' => 'View all services',
                
            ],
            [
                'name' => 'get_service',
                'display_name' => 'View a service',
                'description' => 'View a service',
            ],
            [
                'name' => 'create_service',
                'display_name' => 'Create a service',
                'description' => 'Create a service',
                
            ],
            [
                'name' => 'update_service',
                'display_name' => 'Update a service',
                'description' => 'Update a service',
                
            ],
            [
                'name' => 'delete_service',
                'display_name' => 'Erase a service',
                'description' => 'Erase a service',
                
            ],
        ];
            //MODIFICATION
        $dataModification = [
             [
                'name' => 'get_modifications',
                'display_name' => 'View all modifications',
                'description' => 'View all modifications',
                
            ],
            [
                'name' => 'get_modification',
                'display_name' => 'View a modification',
                'description' => 'View a modification',
            ],
            [
                'name' => 'create_modification',
                'display_name' => 'Create a modification',
                'description' => 'Create a modification',
                
            ],
            [
                'name' => 'update_modification',
                'display_name' => 'Update a modification',
                'description' => 'Update a modification',
                
            ],
            [
                'name' => 'delete_modification',
                'display_name' => 'Erase a modification',
                'description' => 'Erase a modification',
                
            ],
        ];
            //CARRIER
        $dataCarrier = [
            [
                'name' => 'get_carriers',
                'display_name' => 'View all carriers',
                'description' => 'View all carriers',
                
            ],
            [
                'name' => 'get_carrier',
                'display_name' => 'View a carrier',
                'description' => 'View a carrier',
            ],
            [
                'name' => 'create_carrier',
                'display_name' => 'Create a carrier',
                'description' => 'Create a carrier',
                
            ],
            [
                'name' => 'update_carrier',
                'display_name' => 'Update a carrier',
                'description' => 'Update a carrier',
                
            ],
            [
                'name' => 'delete_carrier',
                'display_name' => 'Erase a carrier',
                'description' => 'Erase a carrier',
                
            ],
        ];
            //PRICE
        $dataPrice = [
             [
                'name' => 'get_prices',
                'display_name' => 'View all prices',
                'description' => 'View all prices',
                
            ],
            [
                'name' => 'get_price',
                'display_name' => 'View a price',
                'description' => 'View a price',
            ],
            [
                'name' => 'create_price',
                'display_name' => 'Create a price',
                'description' => 'Create a price',
                
            ],
            [
                'name' => 'update_price',
                'display_name' => 'Update a price',
                'description' => 'Update a price',
                
            ],
            [
                'name' => 'delete_price',
                'display_name' => 'Erase a price',
                'description' => 'Erase a price',
                
            ],
        ];
            //IMAGE
        $dataImage = [
             [
                'name' => 'get_images',
                'display_name' => 'View all images',
                'description' => 'View all images',
                
            ],
            [
                'name' => 'get_image',
                'display_name' => 'View a image',
                'description' => 'View a image',
            ],
            [
                'name' => 'get_image_info',
                'display_name' => 'View a image info',
                'description' => 'View a image info',
            ],
            [
                'name' => 'create_image',
                'display_name' => 'Create a image',
                'description' => 'Create a image',
                
            ],
            [
                'name' => 'update_image',
                'display_name' => 'Update a image',
                'description' => 'Update a image',
                
            ],
            [
                'name' => 'delete_image',
                'display_name' => 'Erase a image',
                'description' => 'Erase a image',
                
            ],
        ];
            //ADDRESS
        $dataAddress = [
            [
                'name' => 'get_addresses',
                'display_name' => 'View all addresses',
                'description' => 'View all addresses',
                
            ],
            [
                'name' => 'get_address',
                'display_name' => 'View a address',
                'description' => 'View a address',
            ],
            [
                'name' => 'create_address',
                'display_name' => 'Create a address',
                'description' => 'Create a address',
                
            ],
            [
                'name' => 'update_address',
                'display_name' => 'Update a address',
                'description' => 'Update a address',
                
            ],
            [
                'name' => 'delete_address',
                'display_name' => 'Erase a address',
                'description' => 'Erase a address',
                
            ],
        ];
            //DEVICETYPE
        $dataDeviceType = [
            [
                'name' => 'get_devicetypes',
                'display_name' => 'View all devicetypes',
                'description' => 'View all devicetypes',
                
            ],
            [
                'name' => 'get_devicetype',
                'display_name' => 'View a devicetype',
                'description' => 'View a devicetype',
            ],
            [
                'name' => 'create_devicetype',
                'display_name' => 'Create a devicetype',
                'description' => 'Create a devicetype',
                
            ],
            [
                'name' => 'update_devicetype',
                'display_name' => 'Update a devicetype',
                'description' => 'Update a devicetype',
                
            ],
            [
                'name' => 'delete_devicetype',
                'display_name' => 'Erase a devicetype',
                'description' => 'Erase a devicetype',
                
            ],
        ];
            //CATEGORYDEVICES
        $dataCategoryDevice = [
            [
                'name' => 'get_categorydevices',
                'display_name' => 'View all devices category',
                'description' => 'View all devices category',
                
            ],
            [
                'name' => 'get_categorydevice',
                'display_name' => 'View a device category',
                'description' => 'View a device category',
            ],
            [
                'name' => 'create_categorydevice',
                'display_name' => 'Create a device category',
                'description' => 'Create a device category',
                
            ],
            [
                'name' => 'update_categorydevice',
                'display_name' => 'Update a device category',
                'description' => 'Update a device category',
                
            ],
            [
                'name' => 'delete_categorydevice',
                'display_name' => 'Erase a device category',
                'description' => 'Erase a device category',
                
            ],
        ];
            //CATEGORYAPPS
        $dataCategoryApps = [
            [
                'name' => 'get_categoryapps',
                'display_name' => 'View all apps category',
                'description' => 'View all apps category',
                
            ],
            [
                'name' => 'get_categoryapp',
                'display_name' => 'View a app category',
                'description' => 'View a app category',
            ],
            [
                'name' => 'create_categoryapp',
                'display_name' => 'Create a app category',
                'description' => 'Create a app category',
                
            ],
            [
                'name' => 'update_categoryapp',
                'display_name' => 'Update a app category',
                'description' => 'Update a app category',
                
            ],
            [
                'name' => 'delete_categoryapp',
                'display_name' => 'Erase a app category',
                'description' => 'Erase a app category',
                
            ],
        ];
            //CONDITIONS
        $dataConditions = [
             [
                'name' => 'get_conditions',
                'display_name' => 'View all conditions',
                'description' => 'View all conditions',
                
            ],
            [
                'name' => 'get_condition',
                'display_name' => 'View a condition',
                'description' => 'View a condition',
            ],
            [
                'name' => 'create_condition',
                'display_name' => 'Create a condition',
                'description' => 'Create a condition',
                
            ],
            [
                'name' => 'update_condition',
                'display_name' => 'Update a condition',
                'description' => 'Update a condition',
                
            ],
            [
                'name' => 'delete_condition',
                'display_name' => 'Erase a condition',
                'description' => 'Erase a condition',
                
            ],
        ];
            //CONDITIONsFIELDs
        $dataConditionsFields = [
             [
                'name' => 'get_ conditionsfields',
                'display_name' => 'View all  conditions fields',
                'description' => 'View all  conditions fields',
                
            ],
        ];
            //CONDITIONsOPERATOR
        $dataConditionsOperator = [
             [
                'name' => 'get_ conditionsoperators',
                'display_name' => 'View all  conditions operators',
                'description' => 'View all  conditions operators',
                
            ],
        ];
        

        $this->loadTable($dataRoles);
        $this->loadTable($dataPermissions);
        $this->loadTable($dataScopes);
        $this->loadTable($dataCompanies);
        $this->loadTable($dataUser);
        $this->loadTable($dataAssets);
        $this->loadTable($dataDevices);
        $this->loadTable($dataContents);
        $this->loadTable($dataApp);
        $this->loadTable($dataOrder);
        $this->loadTable($dataRequest);
        $this->loadTable($dataPackage);
        $this->loadTable($dataService);
        $this->loadTable($dataModification);
        $this->loadTable($dataCarrier);
        $this->loadTable($dataPrice);
        $this->loadTable($dataImage);
        $this->loadTable($dataAddress);
        $this->loadTable($dataDeviceType);
        $this->loadTable($dataCategoryDevice);
        $this->loadTable($dataCategoryApps);
        $this->loadTable($dataConditions);
        $this->loadTable($dataConditionsFields);
        $this->loadTable($dataConditionsOperator);
        

    }
}
