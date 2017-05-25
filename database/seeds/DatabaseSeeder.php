<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = OFF');
                break;
        }

        $this->call(AddressTableSeeder::class);
        $this->call(AllocationsTableSeeder::class);
        $this->call(AppsTableSeeder::class);
        $this->call(AssetsTableSeeder::class);
        $this->call(CarrierImagesTableSeeder::class);
        $this->call(CarriersTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(CompanyAddressTableSeeder::class);
        $this->call(CompanyDomainsTableSeeder::class);
        $this->call(CompanySaml2TableSeeder::class);
        $this->call(ConditionFieldsTableSeeder::class);
        $this->call(ConditionOperatorsTableSeeder::class);
        $this->call(ConditionsTableSeeder::class);
        //$this->call(DeviceCarriersTableSeeder::class);
        //$this->call(DeviceCompaniesTableSeeder::class);
        $this->call(DeviceImagesTableSeeder::class);
        $this->call(DeviceModificationsTableSeeder::class);
        $this->call(DeviceVariationsTableSeeder::class);
        $this->call(DeviceVariationsModificationsTableSeeder::class);
        $this->call(DevicesTableSeeder::class);
        $this->call(DeviceTypeSeeder::class);
        //$this->call(DeviceTypeSeeder::class);
        $this->call(ImagesTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(ModificationsTableSeeder::class);
        //$this->call(OAuthTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);	
        $this->call(OrderDeviceVariationsTableSeeder::class);
        $this->call(OrderAppsTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(PackageAddressTableSeeder::class);
        $this->call(PackageAppsTableSeeder::class);
        $this->call(PackageDevicesTableSeeder::class);
        $this->call(PackageServicesTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRolesTableSeeder::class);
        //$this->call(PagesTableSeeder::class);
        //$this->call(ProvidersTableSeeder::class);
        $this->call(RequestsTableSeeder::class);
        $this->call(RolesTableSeeder::class);     
        $this->call(RoleUsersTableSeeder::class);
        $this->call(ServiceItemsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(ScopesTableSeeder::class);
        $this->call(ScopePermissionsTableSeeder::class);
	    $this->call(UdlValuesTableSeeder::class);
        $this->call(UdlsTableSeeder::class);
        //$this->call(UserDevicesTableSeeder::class);
        //$this->call(UserRolesTableSeeder::class);
        $this->call(UserAddressTableSeeder::class);
        $this->call(UserUdlsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PresetTableSeeder::class);
        $this->call(PresetDeviceVariationTableSeeder::class);
        $this->call(DeviceVariationImagesTableSeeder::class);
        switch (DB::getDriverName()) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = ON');
                break;
        }
    }
}
