<?php

namespace WA\Repositories\User;

use WA\Services\Cache\CacheInterface;
use WA\Services\Form\User\UserForm;

class UserCacheDecorator extends UserDecorator
{
    /**
     * @var \WA\Services\Cache\CacheInterface
     */
    protected $cache;

    /**
     * @param UserInterface  $nextUser
     * @param CacheInterface $cache
     */
    public function __construct(UserInterface $nextUser, CacheInterface $cache)
    {
        parent::__construct($nextUser);

        $this->cache = $cache;
    }

    public function setCriteria($criteria = [])
    {
        $this->nextUser->setCriteria($criteria);
    }

    /**
     * Try to get pagination from cache.
     *
     * @param int  $page
     * @param int  $limit
     * @param bool $all
     *
     * @return mixed
     */
    public function byPage($page = 1, $limit = 10, $all = false)
    {
        $key = md5('page'.$page.'.'.$limit);

//        if ($this->cache->has($key)) {
//            return $this->cache->get($key);
//        }

        $paginated = $this->nextUser->byPage($page, $limit, $all);

        $this->cache->put($key, $paginated);

        return $paginated;
    }

    /**
     * Try to get first name from cache.
     *
     * @param $firstName
     *
     * @return mixed|object
     */
    public function byFirstName($firstName)
    {
        $key = $this->makeKey('firstName.', $firstName);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $user = $this->nextUser->byFirstName($firstName);

        $this->cache->put($key, $user);

        return $user;
    }

    /**
     * Try to retrieve from Cache.
     *
     * @param $lastName
     *
     * @return mixed|object
     */
    public function byLastName($lastName)
    {
        $key = $this->makeKey('lastName', $lastName);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $user = $this->nextUser->byLastName($lastName);
        $this->cache->put($key, $user);

        return $user;
    }

    /**
     * Get employee information by supervisor email.
     *
     * @param string $email
     *
     * @return object of employee information
     */
    public function bySupervisorEmail($email)
    {
        $key = $this->makeKey('supervisorEmail', $email);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $user = $this->nextUser->bySupervisorEmail($email);
        $this->cache->put($key, $user);

        return $user;
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
        $key = $this->makeKey('employeeEmail', $userEmail);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $user = $this->nextUser->byEmail($userEmail);
        $this->cache->put($key, $user);

        return $user;
    }

    /**
     * Get employee supervisor information by supervisor email.
     *
     * @param string $email of employee
     *
     * @return object of employee information
     */
    public function getSupervisor($email = null)
    {
        $key = $this->makeKey('employeeEmail', $email);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $user = $this->nextUser->getSupervisor($email);
        $this->cache->put($key, $user);

        return $user;
    }

    /**
     * Generate a cache key.
     *
     * @param $slug
     * @param $name
     *
     * @return string
     */
    protected function makeKey(
        $slug,
        $name
    ) {
        return md5($slug.'.'.$name);
    }

    /**
     * Get users by search term.
     *
     * @param string $query
     * @param int    $page
     * @param int    $limit
     * @param bool   $paginate
     */
    public function bySearch($query, $page = 1, $limit = 10, $paginate = true)
    {
        return $this->nextUser->bySearch($query, $page, $limit, $paginate);
    }

    /**
     * Get all the validators (eventually by departments).
     *
     * @param string $companyName
     * @param string $department
     *
     * @return object object of validator
     */
    public function getValidators($companyName = null, $department = null)
    {
        return $this->nextUser->getValidators($companyName, $department);
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
        if (is_null($companyId)) {
            return null;
        }

        $key = $this->makeKey('supervisors', $companyId);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $supervisors = $this->nextUser->getAllSupervisors($companyId);
        $this->cache->put($key, $supervisors);

        return $supervisors;
    }

    /**
     * Get an employee information by API Token.
     *
     * @param string $token
     *
     * @return object of employee information
     */
    public function byToken($token)
    {
        $key = $this->makeKey('apiToken', $token);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $user = $this->nextUser->byToken($token);
        $this->cache->put($key, $user);

        return $user;
    }

    /**
     * Get the transformer used by this model.
     *
     * @return object
     */
    public function getTransformer()
    {
        return $this->nextUser->getTransformer();
    }

    /**
     * Get employee by name or email.
     *
     * @param $name
     *
     * @return object of employee
     */
    public function byUsernameOrEmail($name)
    {
        $key = $this->makeKey('name', $name);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $user = $this->nextUser->byUsernameOrEmail($name);
        $this->cache->put($key, $user);

        return $user;
    }

    /**
     * get the model being used on the object.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->nextUser->getModel();
    }

    /**
     * Get Udls values for employee.
     *
     * @param $userId
     *
     * @return object
     */
    public function getUdls($userId)
    {
        $key = $this->makeKey('employeeudls', $userId);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $user = $this->nextUser->getUdls($userId);
        $this->cache->put($key, $user);

        return $user;
    }

    /**
     * Delete an Users.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true)
    {
        return $this->nextUser->delete($id, $soft = true);
    }

    /**
     * Get the list of internal tables that an external system can map to.
     *
     * @return array
     */
    public function getMappableFields()
    {
        return $this->nextUser->getMappableFields();
    }

    /**
     * Get validator information by companyId.
     *
     * @param int $companyId of company selected
     *
     * @return array of employee validator information
     */
    public function getAllValidators($companyId = null)
    {
        $key = $this->makeKey('validators', $companyId);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $user = $this->nextUser->getAllValidators($companyId);
        $this->cache->put($key, $user);

        return $user;
    }

    /**
     * Delete from the repo by the ID.
     *
     * @param int  $id
     * @param bool $force completely remove for the DB instead of marking it as "deleted"
     *
     * @return bool of the effect of the creation
     */
    public function deleteById($id, $force = false)
    {
        return $this->nextUser->deleteById($id, $force = false);
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
     * Check if this employee has asset attached.
     *
     * @param $userId
     * @param $assetId
     *
     * @return mixed
     */
    public function hasAsset($userId, $assetId)
    {
        return $this->nextUser->hasAsset($userId, $assetId);
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
        return $this->nextUser->hasDevice($userId, $deviceId);
    }

    /**
     * @param int $companyIdentifier
     *
     * @return object Object of employee information
     */
    public function byCompanyIdentifier($companyIdentifier)
    {
        return $this->nextUser->byCompanyIdentifier($companyIdentifier);
    }

    /**
     * Get users by their company  id.
     *
     * @param int $companyId
     *
     * @return object Object of users information
     */
    public function byCompanyId($companyId)
    {
        return $this->nextUser->byCompanyId($companyId);
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
        return $this->nextUser->update($data);
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
        return $this->nextUser->syncSupervisor($userEmail);
    }

    /**
     * Get an employee by company ID Or Email.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function byCompanyIdOrEmail(array $data)
    {
        return $this->nextUser->byCompanyIdOrEmail($data);
    }

    /**
     * Get users by their identification (generated CLEAN ID).
     *
     * @param string $identification
     *
     * @return object Object of users information
     */
    public function byIdentification($identification)
    {
        return $this->nextUser->byIdentification($identification);
    }

    /**
     * Get the maximum value of the external ID.
     *
     * @return int
     */
    public function getMaxExternalId()
    {
        return $this->nextUser->getMaxExternalId();
    }

    /**
     * Get the external Id of the employee.
     *
     * @param $identifier
     *
     * @return int
     */
    public function getExternalId($identifier)
    {
        return $this->nextUser->getExternalId($identifier);
    }

    /**
     * Update External Id based on Identifier.
     *
     * @param $identification
     * @param $externalId
     *
     * @return mixed
     */
    public function updateExternalId($identification, $externalId)
    {
        return $this->nextUser->updateExternalId($identification, $externalId);
    }

    /**
     * Get identification values of users with missing external Ids.
     *
     * @return array
     */
    public function getMissingExternalIds()
    {
        return $this->nextUser->getMissingExternalIds();
    }

    /**
     * Get the Roles assigned to a user by user Id.
     *
     * @param int
     *
     * @return mixed
     */
    public function getRoles($id)
    {
        return $this->nextUser->getRoles($id);
    }
}
