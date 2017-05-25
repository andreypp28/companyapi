<?php

namespace WA\Repositories\Company;

use WA\Repositories\RepositoryInterface;

/**
 * Interface CompanyInterface.
 */
interface CompanyInterface extends RepositoryInterface
{
    /**
     * Get the company information by its name.
     *
     * @param $name
     *
     * @return object of the company information
     */
    public function byName($name);

    /**
     * Creates new employee for a company.
     *
     * @param int   $id   of the company
     * @param array $user
     *
     * @return bool true successful | false
     */
    public function addUser($id, array $user);

    /**
     * Get the total amount of employee.
     *
     * @param int  $id   of the company
     * @param bool $sync with external system (EasyVista in our case)
     *
     * @return int count of employee
     */
    public function getUsersCount($id, $sync = true);

    /**
     * Update an employee for a company.
     *
     * @param array $user
     *
     * @return bool true successful | false
     */
    public function updateUser(array $user);

    /**
     * Creates UDLs for a company.
     *
     * @param int   $id   of company
     * @param array $udls
     *
     * return bool
     */
    public function createUDLs($id, array $udls);

    /**
     * Get a company's account summary information.
     *
     * @param $id
     *
     * @return object object of account information
     */
    public function getAccountSummariesById($id);

    /**
     * Get a company's carriers.
     *
     * @param $id
     */
    //public function getCarriers($id);

    /**
     * Get a company's UDLs.
     *
     * @return array of UDL and Values
     */
    public function getUDLs($id);

    /**
     * Get a company's devices.
     *
     * @param $id
     */
    //public function getDevices($id);

    public function getPresets($id);
    
    public function getPackages($id);
    
    public function getDeviceVariations($id);

    /**
     * Get all active companies.
     *
     * @param int|true $isActive
     *
     * @return mixed
     */
    public function getActive($isActive = 1);

    /**
     * Get a company's account details.
     *
     * @param $id
     * @param $billingAccountNumber
     * @param $billMonth
     */
    public function getAccountDetails($id, $billingAccountNumber, $billMonth);

    /**
     * Get the class transformer.
     */
    public function getTransformer();

    /**
     * Given some UDL, it gets the matching path ID.
     *
     * @param int   $id         company id
     * @param array $udls       the values to match
     * @param bool  $externalId should return the externalId instead | false
     * @param int   $creatorId  current user id
     * @param array $userInfo   Information on user being created/edited
     *
     * @return int ID value if there is a match | null id there is no match found
     */
    public function getUdlValuePathId($id, array $udls, $externalId, $creatorId, array $userInfo);

    /**
     * Get the list of internal UDL tables that an external system can map to by companyId.
     *
     * @param int $companyId
     *
     * @return array
     */
    public function getMappableUdlFields($companyId);

    /**
     * Get a company census process rules.
     *
     * @param int    $companyId
     * @param string $type
     *
     * @return array
     */
    public function getCensusRules($companyId, $type);

    /**
     * Get live/demo status of a company.
     *
     * @param $id
     */
    public function getLiveStatus($id);

    /**
     * Create a new Company.
     *
     * @param array $data
     *
     * @return object object of the company | false
     */
    public function create(
        array $data
    );

    /**
     * Delete a Company.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);

    /**
     * Update a company.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Get Pool Bases by Company Id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getPools($id);

    /**
     * Get Company Specific Carriers.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getCompanySpecific($id);

    /**
     * Gets the Id of a company by the email
     * (does a best guess based on the allowed domain, returns a 0 is no matcH).
     *
     * @param string $email
     *
     * @return int
     */
    public function getIdByUserEmail($email);

    /**
     * Get company domains by the ID
     * if no id is defined it gets all.
     *
     * @param int|null $companyId
     *
     * @return array
     */
    public function getDomains($companyId = null);
}
