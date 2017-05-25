<?php

namespace WA\Repositories\User;

use WA\Repositories\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{
    /**
     * Create a new User.
     *
     * @param array $data
     *
     * @return object object of the employee | false
     */
    public function create(array $data);

    /**
     * Get users by search term.
     *
     * @param string $query
     * @param int    $page
     * @param int    $limit
     * @param bool   $paginate
     */
    public function bySearch($query, $page = 1, $limit = 10, $paginate = true);

    /**
     * Get employee information by supervisor email.
     *
     * @param string $email
     *
     * @return object of employee information
     */
    public function bySupervisorEmail($email);

    /**
     * Get employee supervisor information by supervisor email.
     *
     * @param string $email of employee
     *
     * @return object of employee information
     */
    public function getSupervisor($email = null);

    /**
     * Get all the validators (eventually by departments).
     *
     * @param string $companyName
     * @param string $department
     *
     * @return object object of validator
     */
    public function getValidators($companyName = null, $department = null);

    /**
     * Get employee by lastName.
     *
     * @param $lastName
     *
     * @return object Object of employee information
     */
    public function byLastName($lastName);

    /**
     * Get an employee by their company identifier.
     *
     * @param int $companyIdentifier
     *
     * @return object Object of employee information
     */
    public function byCompanyIdentifier($companyIdentifier);

    /**
     * Get users by their company  id.
     *
     * @param int $companyId
     *
     * @return object Object of users information
     */
    public function byCompanyId($companyId);

    /**
     * Get users by their identification (generated CLEAN ID).
     *
     * @param string $identification
     *
     * @return object Object of users information
     */
    public function byIdentification($identification);

    /**
     * Update a new employee.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Check if this employee has asset attached.
     *
     * @param $userId
     * @param $assetId
     *
     * @return mixed
     */
    public function hasAsset($userId, $assetId);

    /**
     * Check if employee has device attached.
     *
     * @param $userId
     * @param $deviceId
     *
     * @return
     */
    public function hasDevice($userId, $deviceId);

    /**
     * Update an employee's supervisor.
     *
     * @param $userEmail
     *
     * @return bool
     */
    public function syncSupervisor($userEmail);

    /**
     * Get an employee information by email.
     *
     * @param string $userEmail
     *
     * @return object of employee information
     */
    public function byEmail($userEmail);

    /**
     * Get an employee information by API Token.
     *
     * @param string $token
     *
     * @return object of employee information
     */
    public function byToken($token);

    /**
     * Get the transformer used by this model.
     *
     * @return object
     */
    public function getTransformer();

    /**
     * Get employee by name or email.
     *
     * @param $name
     *
     * @return object of employee
     */
    public function byUsernameOrEmail($name);

    /**
     * Get an employee by company ID Or Email.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function byCompanyIdOrEmail(array $data);

    /**
     * get the model being used on the object.
     *
     * @return mixed
     */
    public function getModel();

    /**
     * Get Udls values for employee.
     *
     * @param $userId
     *
     * @return object
     */
    public function getUdls($userId);

    /**
     * Delete an Users.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);

    /**
     * Get the list of internal tables that an external system can map to.
     *
     * @return array
     */
    public function getMappableFields();

    /**
     * Get supervisor information by companyId.
     *
     * @param int $companyId of company selected
     *
     * @return array of employee information
     */
    public function getAllSupervisors($companyId = null);

    /**
     * Get validator information by companyId.
     *
     * @param int $companyId of company selected
     *
     * @return array of employee validator information
     */
    public function getAllValidators($companyId = null);

    /**
     * Get the maximum value of the external ID.
     *
     * @return int
     */
    public function getMaxExternalId();

    /**
     * Get the external Id of the employee.
     *
     * @param $identifier
     *
     * @return int
     */
    public function getExternalId($identifier);

    /**
     * Update External Id based on Identifier.
     *
     * @param $identification
     * @param $externalId
     *
     * @return mixed
     */
    public function updateExternalId($identification, $externalId);

    /**
     * Get identification values of users with missing external Ids.
     *
     * @return array
     */
    public function getMissingExternalIds();

    /**
     * Get the Roles assigned to a user by user Id.
     *
     * @param int
     *
     * @return mixed
     */
    public function getRoles($id);
}
