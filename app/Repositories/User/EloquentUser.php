<?php

namespace WA\Repositories\User;

use Illuminate\Database\Eloquent\Model;
use Log;
use WA\DataStore\User\User;
use WA\DataStore\User\UserTransformer;
use WA\Repositories\AbstractRepository;
use WA\Repositories\Company\CompanyInterface;
use WA\Repositories\Udl\UdlInterface;
use WA\Repositories\UdlValue\UdlValueInterface;
use WA\Repositories\UdlValuePath\UdlValuePathInterface;
use Webpatser\Uuid\Uuid;
use DB;

class EloquentUser extends AbstractRepository implements UserInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \WA\Repositories\UdlValue\UdlValueInterface
     */
    protected $udlValue;

    /**
     * @var \WA\Repositories\Udl\UdlInterface
     */
    protected $udl;

    /**
     * @param Model $model
     * @param UdlValueInterface $udlValue
     * @param UdlInterface $udl
     */
    public function __construct(
        Model $model,
        UdlValueInterface $udlValue,
        UdlInterface $udl
    ) {
        parent::__construct($model);
        $this->model = $model;
        $this->udl = $udl;
        $this->udlValue = $udlValue;
    }

    /**
     * Get paginated user.
     *
     * @param int $perPage
     * @param bool $paginate
     *
     * @return Object as Collection of object information, | Paginator Collection if pagination is true (default)
     */
    public function byPage($paginate = true, $perPage = 25)
    {
        $query = $this->applyCriteria($this->model);

        if (!$paginate) {
            $ownTable = $query->getTable();

            // manually run the queries
            $response = \DB::table($ownTable)
                ->select(
                    $ownTable.'.id',
                    'firstName',
                    'lastName',
                    'email',
                    'supervisorEmail',
                    'c.name as companyName',
                    'identification'
                )
                ->join('companies as c', 'c.id', '=', $ownTable.'.companyId')
                ->get();

            return $response;
        }

        return $query->paginate($perPage);
    }

    /**
     * Get the object by search.
     *
     * @param string $query
     * @param int    $page
     * @param int    $limit
     * @param bool   $paginate
     *
     * @return \StdClass
     */
    public function bySearch($query, $page = 1, $limit = 10, $paginate = true)
    {
        $result = new \StdClass();
        $result->page = $page;
        $result->limit = $limit;
        $result->totalItems = 0;
        $result->items = [];

        $model = $this->model->where('firstName', $query)
            ->orWhere('lastName', $query)
            ->orWhere('email', $query);

        if ($paginate) {
            $model->skip($limit * ($page - 1))
                ->take($limit);

            $result->items = $model->get();
            $result->totalItems = $model->count();
        } else {
            $result->items = $model->get();
            $result->totalItems = $model->count();
        }

        return $result;
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
        return $this->model
            ->where('isActive', 1)
            ->where('lastName', $lastName)
            ->first();
    }

    /**
     * @param int $id
     * @param bool $active
     *
     * @return Object Object of employee information
     */
    public function byId($id, $active = null)
    {
        $query = $this->applyCriteria($this->model);

        if (!empty($active)) {
            $query = $query->where('isActive', (int) $active);
        }

        $response = null;

        // We want to allow for the passing of  multiple ID (for smarted API)
        if (is_array($id)) {
            if (count($id) == 1) {
                $response = $query->where('id', $id[0])->first();

                return $response;
            } else {
                $response = $query->whereIn('id', $id)
                    ->get();

                return $response;
            }
        }

        $em = $query->where('id', $id)->first();

        return $em;
    }

    /**
     * @param $companyId
     *
     * @return object Object of employee information
     */
    public function byCompanyId($companyId)
    {
        $ownTable = $this->model->getTable();

        // manually run the queries
        $response = \DB::table($ownTable)
            ->select(
                $ownTable.'.id',
                'firstName',
                'lastName', 'email',
                'supervisorEmail',
                'c.name as companyName',
                'identification',
                'isSupervisor'
            )
            ->join('companies as c', 'c.id', '=', $ownTable.'.companyId')
            ->where('companyId', $companyId)
            ->where('deleted_at', null)
            ->get();

        return $response;
    }

    /**
     * Check if this employee has asset attached.
     *
     * @param $modelId
     * @param $assetId
     *
     * @return mixed
     */
    public function hasAsset($modelId, $assetId)
    {
        return (bool)
        $this->model->find($modelId)
            ->assets()->find($assetId);
    }

    /**
     * Check if employee has device attached.
     *
     * @param $modelId
     * @param $deviceId
     *
     * @return bool
     */
    public function hasDevice($modelId, $deviceId)
    {
        return (bool)
        $this->model->find($modelId)
            ->asset()->find($deviceId);
    }

    /**
     * Create a new User.
     *
     * @param array $data
     * @param array $udlValues
     *
     * @return User | false
     */
    public function create(array $data)
    {
        if(isset($data['email']) && $data['email'] !== ''){
            $userData['email'] = $data['email'];
        } else {
            return false;
        }

        if(isset($data['companyId']) && $data['companyId'] !== ''){
            $userData['companyId'] = $data['companyId'];
        } else {
            if (!isset($data['companyId']) || $data['companyId'] == null){
                $domain = explode('@', $data['email'])[1];
                $company = \DB::table('company_domains')
                    ->select('companyId')
                    ->where('domain', $domain)->get();

                if($company !== null && $company->count() > 0){
                    $userData['companyId'] = $company[0]->companyId;
                } else {
                    return false;
                } 
            }
        }

        if(isset($data['uuid']) && $data['uuid'] !== ''){
            $userData['uuid'] = $data['uuid'];
        } else {
            $userData['uuid'] = Uuid::generate(4)->string;
        }

        if(isset($data['identification']) && $data['identification'] !== ''){
            $userData['identification'] = $data['identification'];
        } else {
            $generator = app()->make('WA\Helpers\UserHelper');
            $ident = $generator->generateIds($userData['companyId']);
            if (!$ident) {
                return false;
            }
            $userData['identification'] = $ident;
        }     

        if(isset($data['alternateEmail']) && $data['alternateEmail'] !== ''){
            $userData['alternateEmail'] = $data['alternateEmail'];
        } else {
            $userData['alternateEmail'] = null;
        }

        if(isset($data['password']) && $data['password'] !== ''){
            $userData['password'] = bcrypt($data['password']);
        } else {
            $userData['password'] = bcrypt($userData['identification']);
        }

        if(isset($data['username']) && $data['username'] !== ''){
            $userData['username'] = $data['username'];
        } else {
            $userData['username'] = explode('@', $data['email'])[0];
        }

        if(isset($data['confirmation_code']) && $data['confirmation_code'] !== ''){
            $userData['confirmation_code'] = $data['confirmation_code'];
        } else {
            $userData['confirmation_code'] = null;
        }

        if(isset($data['remember_token']) && $data['remember_token'] !== ''){
            $userData['remember_token'] = $data['remember_token'];
        } else {
            $userData['remember_token'] = null;
        }

        if(isset($data['confirmed']) && $data['confirmed'] !== ''){
            $userData['confirmed'] = $data['confirmed'];
        } else {
            $userData['confirmed'] = 0;
        }

        if(isset($data['firstName']) && $data['firstName'] !== ''){
            $userData['firstName'] = $data['firstName'];
        } else {
            $userData['firstName'] = null;
        }

        if(isset($data['lastName']) && $data['lastName'] !== ''){
            $userData['lastName'] = $data['lastName'];
        } else {
            $userData['lastName'] = null;
        }

        if(isset($data['alternateFirstName']) && $data['alternateFirstName'] !== ''){
            $userData['alternateFirstName'] = $data['alternateFirstName'];
        } else {
            $userData['alternateFirstName'] = null;
        }

        if(isset($data['supervisorEmail']) && $data['supervisorEmail'] !== ''){
            $userData['supervisorEmail'] = $data['supervisorEmail'];
        } else {
            $userData['supervisorEmail'] = null;
        }

        if(isset($data['companyUserIdentifier']) && $data['companyUserIdentifier'] !== ''){
            $userData['companyUserIdentifier'] = $data['companyUserIdentifier'];
        } else {
            $userData['companyUserIdentifier'] = null;
        }

        if(isset($data['isSupervisor']) && $data['isSupervisor'] !== ''){
            $userData['isSupervisor'] = $data['isSupervisor'];
        } else {
            $userData['isSupervisor'] = 0;
        }

        if(isset($data['isValidator']) && $data['isValidator'] !== ''){
            $userData['isValidator'] = $data['isValidator'];
        } else {
            $userData['isValidator'] = 0;
        }

        if(isset($data['isActive']) && $data['isActive'] !== ''){
            $userData['isActive'] = $data['isActive'];
        } else {
            $userData['isActive'] = 0;
        }

        if(isset($data['rgt']) && $data['rgt'] !== ''){
            $userData['rgt'] = $data['rgt'];
        } else {
            $userData['rgt'] = null;
        }

        if(isset($data['lft']) && $data['lft'] !== ''){
            $userData['lft'] = $data['lft'];
        } else {
            $userData['lft'] = null;
        }

        if(isset($data['hierarchy']) && $data['hierarchy'] !== ''){
            $userData['hierarchy'] = $data['hierarchy'];
        } else {
            $userData['hierarchy'] = null;
        }

        if(isset($data['defaultLang']) && $data['defaultLang'] !== ''){
            $userData['defaultLang'] = $data['defaultLang'];
        } else {
            $userData['defaultLang'] = 'en';
        }

        if(isset($data['notes']) && $data['notes'] !== ''){
            $userData['notes'] = $data['notes'];
        } else {
            $userData['notes'] = null;
        }

        if(isset($data['level']) && $data['level'] !== ''){
            $userData['level'] = $data['level'];
        } else {
            $userData['level'] = 0;
        }

        if(isset($data['notify']) && $data['notify'] !== ''){
            $userData['notify'] = $data['notify'];
        } else {
            $userData['notify'] = 0;
        }

        if(isset($data['syncId']) && $data['syncId'] !== ''){
            $userData['syncId'] = $data['syncId'];
        } else {
            $userData['syncId'] = null;
        }

        if(isset($data['supervisorId']) && $data['supervisorId'] !== ''){
            $userData['supervisorId'] = $data['supervisorId'];
        } else {
            $userData['supervisorId'] = null;
        }

        if(isset($data['externalId']) && $data['externalId'] !== ''){
            $userData['externalId'] = $data['externalId'];
        } else {
            $userData['externalId'] = null;
        }

        if(isset($data['approverId']) && $data['approverId'] !== ''){
            $userData['approverId'] = $data['approverId'];
        } else {
            $userData['approverId'] = null;
        }

        if(isset($data['defaultLocationId']) && $data['defaultLocationId'] !== ''){
            $userData['defaultLocationId'] = $data['defaultLocationId'];
        } else {
            $userData['defaultLocationId'] = null;
        }

        if(isset($data['departmentId']) && $data['departmentId'] !== ''){
            $userData['departmentId'] = $data['departmentId'];
        } else {
            $userData['departmentId'] = null;
        }

        $user = $this->model->create($userData);

        if (!$user) {
            return false;
        }

        return $user;
    }

    /**
     * @param $companyId
     *
     * @return object Object of employee information
     */
    public function byCompanyIdentifier($companyId)
    {
        $run = $this->model
            ->where('companyUserIdentifier', $companyId)
            ->where('isActive', 1)
            ->first();

        return $run;
    }

    /**
     * Get an employee information by email.
     *
     * @param string $userEmail
     *
     * @return Object of employee information
     */
    public function byEmail($userEmail)
    {
        return
            $this->model->where('email', $userEmail)
                ->first();
    }

    /**
     * verify if this employee model is updatable.
     *
     * @param Model $user
     * @param array $data
     *
     * @return bool
     */
    protected function isUpdatable(Model $user, array $data)
    {
        if ($user->firstName !== $data['firstName'] ||
            $user->lastName !== $data['lastName'] ||
            $user->email !== $data['email'] ||
            $user->supervisorEmail !== $data['supervisorEmail']
        ) {
            $this->update(array_merge(['id' => $user->id], $data));
        }

        return true;
    }

    /**
     * Update a new employee.
     *
     * @param array $data
     * @param array $udlValues
     *
     * @return bool
     */
    public function update(array $data)
    {
        $user = $this->model->find($data['id']);

        if (!$user) {
            return 'notExist';
        }

        if(isset($data['uuid']) && $data['uuid'] !== ''){
            $user->uuid = $data['uuid'];
        }

        if(isset($data['identification']) && $data['identification'] !== ''){
            $user->identification = $data['identification'];
        }

        if(isset($data['email']) && $data['email'] !== ''){
            $user->email = $data['email'];
        }

        if(isset($data['alternateEmail']) && $data['alternateEmail'] !== ''){
            $user->alternateEmail = $data['alternateEmail'];
        }

        if(isset($data['password']) && $data['password'] !== ''){
            $user->password = bcrypt($data['password']);
        }

        if(isset($data['username']) && $data['username'] !== ''){
            $user->username = $data['username'];
        }

        if(isset($data['confirmation_code']) && $data['confirmation_code'] !== ''){
            $user->confirmation_code = $data['confirmation_code'];
        }

        if(isset($data['remember_token']) && $data['remember_token'] !== ''){
            $user->remember_token = $data['remember_token'];
        }

        if(isset($data['confirmed']) && $data['confirmed'] !== ''){
            $user->confirmed = $data['confirmed'];
        }

        if(isset($data['firstName']) && $data['firstName'] !== ''){
            $user->firstName = $data['firstName'];
        }

        if(isset($data['lastName']) && $data['lastName'] !== ''){
            $user->lastName = $data['lastName'];
        }

        if(isset($data['alternateFirstName']) && $data['alternateFirstName'] !== ''){
            $user->alternateFirstName = $data['alternateFirstName'];
        }

        if(isset($data['supervisorEmail']) && $data['supervisorEmail'] !== ''){
            $user->supervisorEmail = $data['supervisorEmail'];
        }

        if(isset($data['companyUserIdentifier']) && $data['companyUserIdentifier'] !== ''){
            $user->companyUserIdentifier = $data['companyUserIdentifier'];
        }

        if(isset($data['isSupervisor']) && $data['isSupervisor'] !== ''){
            $user->isSupervisor = $data['isSupervisor'];
        }

        if(isset($data['isValidator']) && $data['isValidator'] !== ''){
            $user->isValidator = $data['isValidator'];
        }

        if(isset($data['isActive']) && $data['isActive'] !== ''){
            $user->isActive = $data['isActive'];
        }

        if(isset($data['rgt']) && $data['rgt'] !== ''){
            $user->rgt = $data['rgt'];
        }

        if(isset($data['lft']) && $data['lft'] !== ''){
            $user->lft = $data['lft'];
        }

        if(isset($data['hierarchy']) && $data['hierarchy'] !== ''){
            $user->hierarchy = $data['hierarchy'];
        }

        if(isset($data['defaultLang']) && $data['defaultLang'] !== ''){
            $user->defaultLang = $data['defaultLang'];
        }

        if(isset($data['notes']) && $data['notes'] !== ''){
            $user->notes = $data['notes'];
        }

        if(isset($data['level']) && $data['level'] !== ''){
            $user->level = $data['level'];
        }

        if(isset($data['notify']) && $data['notify'] !== ''){
            $user->notify = $data['notify'];
        }

        if(isset($data['companyId']) && $data['companyId'] !== ''){
            $user->companyId = $data['companyId'];
        }

        if(isset($data['syncId']) && $data['syncId'] !== ''){
            $user->syncId = $data['syncId'];
        }

        if(isset($data['supervisorId']) && $data['supervisorId'] !== ''){
            $user->supervisorId = $data['supervisorId'];
        }

        if(isset($data['externalId']) && $data['externalId'] !== ''){
            $user->externalId = $data['externalId'];
        }

        if(isset($data['approverId']) && $data['approverId'] !== ''){
            $user->approverId = $data['approverId'];
        }

        if(isset($data['defaultLocationId']) && $data['defaultLocationId'] !== ''){
            $user->defaultLocationId = $data['defaultLocationId'];
        }

        if(isset($data['departmentId']) && $data['departmentId'] !== ''){
            $user->departmentId = $data['departmentId'];
        }

        if (!$user->save()) {
            return 'notSaved';
        }

        return $user;
    }

    /**
     * For every employee, attach their respective UDL values.
     * this expects an array of the key -> value: udl Name = udlValue pairing.
     *
     * @param Model $user
     * @param array $values [(string) udl => (string) udlValue]
     *
     * @return bool
     */
    protected function syncUDLValues(Model $user, array $values)
    {
        foreach ($values as $udl => $value) {
            $base_udl_value = null;
            $udl_id = null;

            if (!is_array($value)) {
                $udl_id = !is_null($udl_val = $this->udl->byName($udl)) ? $udl_val->id : null;
                $base_udl_value = $value;
            } else {
                foreach ($value as $udl_id_init => $base_udl_value_init) {
                    $udl_id = $udl_id_init;
                    $base_udl_value = $base_udl_value_init['value'];
                }
            }

            if (empty($base_udl_value)) {
                continue;
            }

            $udl_value = $this->udlValue->byNameOrCreate($base_udl_value, $udl_id, $user['companyId']);

            if (isset($udl_value['id'])) {
                $udlValues[] = $udl_value['id'];
            } else {
                Log::info('The UDL: '.$udl_value.'was not found');
                continue;
            }
        }

        if (!empty($udlValues)) {
            $user->udlValues()->sync($udlValues);
        }

        return true;
    }

    /**
     * Get employee information by supervisor email.
     *
     * @param string $email
     *
     * @return Object of employee information | null
     */
    public function bySupervisorEmail($email)
    {
        return $this->model->where('supervisorEmail', $email)
            ->where('isActive', 1)
            ->first();
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
        $supervisor = $this->getSupervisor($userEmail);
        $user = $this->byEmail($userEmail);

        try {
            if (empty($supervisor)) {
                return false;
            }

            if ($user->isSelfOrDescendantOf($supervisor)) {
                return false;
            }

            if (!$user->makeChildOf($supervisor)) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("There was an issue assigning employee:  $user->email  to $supervisor->email  <<>>".$e->getMessage());
            return;
        }
    }

    /**
     * Get employee supervisor information by supervisor email.
     *
     * @param string $email of employee
     *
     * @return Object of employee information
     */
    public function getSupervisor($email = null)
    {
        if (!is_null($email)) {
            $supervisorEmail = $this->byEmail($email)->supervisorEmail;

            return
                $this->model->where('email', $supervisorEmail)
                    ->where('isActive', 1)
                    ->first();
        }

        return
            $this->model->where('isSupervisor', 1)
                ->groupBy('email')
                ->get();
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
        return $this->model->where('isValidator', 1)
            ->groupBy('email')
            ->get();
    }

    protected function totalUsers(array $whereClause = [])
    {
        $model = $this->model->where('isActive', 1);

        if (!empty($whereClause)) {
            $model->where($whereClause[0], $whereClause[1]);
        }

        return $model->count();
    }

    /**
     * Get the transformer used by this model.
     *
     * @return object
     */
    public function getTransformer()
    {
        return new UserTransformer();
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
        $model = $this->model;

        $response =
            $model->where('username', $name)
                ->orWhere('email', $name)
                ->first();

        return $response;
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
        return
            $this->model->where('apiToken', $token)
                ->first();
    }

    /**
     * Get Udls values for employeeId
     *
     * @return Object with employee specific UDLs
     */
    public function getUdls($userId)
    {
        $udls = $this->model->find($userId)->udlValues()->get();

        return $udls;
    }

    /**
     * Get Address for employeeId
     *
     * @return Object with employee specific Address
     */
    public function getAddress($userId)
    {
        $udls = $this->model->find($userId)->address()->get();

        return $udls;
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
        if (!$this->model->find($id)) {
            return false;
        }

        if (!$soft) {
            $this->model->forceDelete($id);
        }

        return $this->model->destroy($id);
    }

    /**
     * Get the list of internal tables that an external system can map to.
     *
     * @return array
     */
    public function getMappableFields()
    {
        return [
            'Main Email' => 'email',
            'Alternate Email' => 'alternateEmail',
//            'User Name' => 'username',
            'First Name' => 'firstName',
            'Alternate First Name' => 'alternateFirstName',
            'Last Name' => 'lastName',
            'Supervisor Email' => 'supervisorEmail',
            'Company Identification' => 'companyUserIdentifier',
            'Location' => 'defaultLocationId',
            'Notes' => 'notes',
        ];
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

        return $this->model->where('companyId', $companyId)
            ->where('isSupervisor', 1)
            ->get()->toArray();
    }

    /**
     * Get validator information by companyId.
     *
     * @param int $companyId of company selected
     *
     * @return Array of employee validator information
     */
    public function getAllValidators($companyId = null)
    {
        if (is_null($companyId)) {
            return null;
        }

        return $this->model->where('companyId', $companyId)
            ->where('isValidator', 1)
            ->get()->toArray();
    }

    /**
     * Gets the external Id of a company (uses this as a last resort if the ID is not provided in the load).
     *
     * @param                  $companyId
     * @param CompanyInterface $company
     *
     * @return int of the companyId
     */
    private function getCompanyExternalId($companyId, CompanyInterface $company = null)
    {
        $company = $company ?: app()->make('WA\Repositories\Company\CompanyInterface');

        $company = $company->byId($companyId);

        if (!isset($company)) {
            return null;
        }

        return $company->externalId;
    }

    /**
     * If a supervisor Id is not provided, get it.
     *
     * @param $supervisorEmail
     *
     * @return string of supervisor Email | Null
     */
    private function getExternalSupervisorId($supervisorEmail)
    {
        $supervisor = $this->model->where('email', $supervisorEmail)->first();

        if (!isset($supervisor)) {
            return null;
        }

        return $supervisor->externalId;
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
        return
            $this->model->where('identification', $identification)
                ->first();
    }

    /**
     * Compact UDL values into a format suitable to easily gettting the UDL/Value/Paths.
     *
     * @param array $udlValues
     * @param int $companyId
     *
     * @return array
     */
    private function compactUdlValues(array $udlValues, $companyId)
    {
        $compactValues = [];

        foreach ($udlValues as $key => $value) {
            $udlId = $this->udl->byName($key, $companyId)['id'];

            $compactValues[] = [
                $udlId => ['value' => $value],
            ];
        }

        return $compactValues;
    }

    /**
     * Get the departmental name.
     *
     * @param                            $externalDepartmentPathId
     * @param UdlValuePathInterface|null $udlValuePath
     *
     * @return mixed
     */
    private function getDepartmentPathName($externalDepartmentPathId, UdlValuePathInterface $udlValuePath = null)
    {
        $udlValuePath = $udlValuePath ?: app()->make('WA\DataStore\UdlValuePath\UdlValuePathInterface');

        return $udlValuePath->byExternalId($externalDepartmentPathId)['udlPath'];
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
        if (!array_key_exists('companyUserIdentifier', $data) && !array_key_exists('email', $data)) {
            return null;
        }

        // we need to check by the two key fields to be sure we don't recreate an existing employee
        $user = $this->byCompanyIdentifier($data['companyUserIdentifier']);

        if (empty($user)) {
            $user = $this->byEmail(strtolower($data['email']));
        }

        return $user;
    }

    /**
     * Get the maximum value of the external ID.
     *
     * @return int
     */
    public function getMaxExternalId()
    {
        $externalIdColumnName = 'externalId';

        return (int) $this->model->max($externalIdColumnName);
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
        return $this->model->where('identification', $identifier)->pluck('externalId');
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
        return
            $this->model->where('identification', $identification)
                ->update(['externalId' => $externalId]);
    }

    /**
     * Get identification values of users with missing external Ids.
     *
     * @return array
     */
    public function getMissingExternalIds()
    {
        return $this->model
            ->where('externalId', null)
            ->where('companyId', '<>', 9)// Hard exclude WA users for now.
            ->where('confirmed', 1)
            ->get()->toArray();
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
        return $this->model->where('id', $id)->first()->roles;
    }
}
