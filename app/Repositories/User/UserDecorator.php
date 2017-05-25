<?php

namespace WA\Repositories\User;

use WA\Repositories\AbstractRepository;
use WA\Services\Form\User\UserForm;

abstract class UserDecorator extends AbstractRepository implements UserInterface
{
    protected $nextUser;

    public function __construct(UserInterface $nextUser)
    {
        parent::__construct($nextUser->getModel());
        $this->nextUser = $nextUser;
    }

    public function setCriteria($criteria = [])
    {
        $this->nextUser->setCriteria($criteria);
    }

    /**
     * Get paginated users.
     *
     * @param int $page
     * @param int $limit
     * @param     $all
     *
     * @return mixed
     */
    public function byPage($page = 1, $limit = 10, $all = false)
    {
        $this->nextUser->byPage($page, $limit, $all);
    }

    /**
     * Get employee by lastName.
     *
     * @param $lastName
     *
     * @return object Object of employee information
     */
    public function byLastName($lastName)
    {
        $this->nextUser->byFirstName($lastName);
    }

    /**
     * Check if this employee has asset attached.
     *
     * @param $userId
     * @param $assetId
     *
     * @return mixed
     */
    public function hasAsset($userId, $assetId)
    {
        $this->nextUser->hasAsset($userId, $assetId);
    }

    /**
     * Check if employee has device attached.
     *
     * @param $userId
     * @param $deviceId
     *
     * @return bool
     */
    public function hasDevice($userId, $deviceId)
    {
        $this->nextUser->hasDevice($userId, $deviceId);
    }

    /**
     * @param $id
     *
     * @return object Object of employee information
     */
    public function byId($id)
    {
        return $this->nextUser->byId($id);
    }

    /**
     * @param $companyId
     *
     * @return object Object of employee information
     */
    public function byCompanyIdentifier($companyId)
    {
        $this->nextUser->byCompanyIdentifier($companyId);
    }

    /**
     * Create a new User.
     *
     * @param array    $data
     * @param array    $udlValues
     * @param bool     $pushToExternalService |true
     * @param UserForm $userForm
     *
     * @return object object of the employee | false
     */
    public function create(
        array $data,
        array $udlValues = [],
        $pushToExternalService = true,
        UserForm $userForm = null
    ) {
        $userForm = $userForm ?: app()->make('WA\Services\Form\User\UserForm');

        return $this->nextUser->create($data, $udlValues, $pushToExternalService, $userForm);
    }

    /**
     * Update a new employee.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $this->nextUser->update($data);
    }

    /**
     * Update an employee's supervisor.
     *
     * @param $userEmail
     *
     * @return bool
     */
    public function syncSupervisor($userEmail)
    {
        $this->nextUser->syncSupervisor($userEmail);
    }

    /**
     * @param $firstName
     *
     * @return object Object of employee information
     */
    public function byFirstName($firstName)
    {
        $this->nextUser->byFirstName($firstName);
    }

    /**
     * Get supervisor information by companyId.
     *
     * @param int $companyId of company selected
     *
     * @return array of employee supervisor information
     */
    public function getAllSupervisors($companyId = null)
    {
        $this->nextUser->getAllSupervisors($companyId);
    }

    /**
     * Get an employee information by email.
     *
     * @param string $userEmail
     *
     * @return object of employee information
     */
    public function byEmail($userEmail)
    {
        $this->nextUser->byEmail($userEmail);
    }
}
