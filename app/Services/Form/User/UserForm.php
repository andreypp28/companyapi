<?php

namespace WA\Services\Form\User;

use Illuminate\Session\SessionManager as Session;
use Log;
use WA\Helpers\Traits\SetLimits;
use WA\Http\Controllers\Admin\HelperController;
use WA\Repositories\Company\CompanyInterface;
use WA\Repositories\User\UserInterface;
use WA\Repositories\HelpDesk\HelpDeskInterface;
use WA\Repositories\Permission\PermissionsInterface;
use WA\Repositories\Role\RoleInterface;
use WA\Repositories\Udl\UdlInterface;
use WA\Services\Form\AbstractForm;
use WA\Services\Soap\HelpDeskEasyVista as HelpDeskApi;
use WA\Repositories\Allocation\AllocationInterface;

/**
 * Class UserForm.
 */
class UserForm extends AbstractForm
{
    use SetLimits;
    /**
     * @var UserFormValidator
     */
    protected $validator;

    /**
     * @var \WA\Repositories\User\UserInterface
     */
    protected $user;

    /**
     * @var HelpDeskApi
     */
    protected $helpDeskApi;

    /**
     * @var HelpDeskInterface
     */
    protected $helpDesk;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var
     */
    protected $currentCompany;

    /**
     * @var UdlInterface
     */
    protected $udl;

    /**
     * @var CompanyInterface
     */
    protected $company;

    /**
     * @var RoleInterface
     */
    protected $role;

    /**
     * @var PermissionsInterface
     */
    protected $permissions;

    /**
     * @var AllocationInterface
     */
    protected $allocations;

    /**
     * @param UserInterface       $user
     * @param UserFormValidator   $validator
     * @param HelpDeskApi         $helpDeskApi
     * @param HelpDeskInterface   $helpDesk
     * @param UdlInterface        $udl         *
     * @param Session             $session
     * @param CompanyInterface    $company
     * @param RoleInterface       $role
     * @param AllocationInterface $allocations
     */
    public function __construct(
        UserInterface $user,
//        UserFormValidator $validator,
//        HelpDeskApi $helpDeskApi,
//        HelpDeskInterface $helpDesk,
//        UdlInterface $udl,
//        Session $session,
        CompanyInterface $company,
        RoleInterface $role,
        AllocationInterface $allocations

    ) {
        //        $this->validator = $validator;
        $this->user = $user;
//        $this->helpDeskApi = $helpDeskApi;
//        $this->helpDesk = $helpDesk;
//        $this->udl = $udl;
//        $this->session = $session;
        $this->company = $company;
        $this->role = $role;
        $this->allocations = $allocations;

//        $this->currentCompany = $session->get('clean.company');
    }

    /**
     * @param array $input
     *
     * @return bool|object of employee data
     */
    public function create(array $input)
    {
        $generator = app()->make('WA\Http\Controllers\Admin\HelperController');

        $identification = $generator->generateIds($input['companyId']);
        $input['companyUserIdentifier'] = (!empty($input['companyUserIdentifier'])) ? $input['companyUserIdentifier'] : $identification;
        $input['username'] = (empty($input['username'])) ? strtolower($identification) : $input['username'];

        $input = array_merge($input, ['identification' => $identification]);

        if (!(bool) $input['companyId']) {
            $this->notify('error', 'You must select a company to continue');

            return false;
        }

        // there must be a value select or non-census clients
        if (!$input['isCensusCompany']) {
            $udl_counter = 0;
            foreach ($input['udls'] as $udls => $udl) {
                if ($b = array_pop($udl)['value'] != '') {
                    $udl_counter += 1;
                }
            }

            if ($udl_counter = 0) {
                $this->notify('error', 'You must select Department/UDL Information to continue');

                return false;
            }
        }

        $udl_value_set = 0;

//        if (!(bool)$this->currentCompany->isCensus) {
//            //
//            foreach (array_flatten($input['udls']) as $val) {
//                if (!empty($val)) {
//                    $udl_value_set += 1;
//                }
//            }

//            if ($udl_value_set <= 1) {
//                $this->notify('error', 'You must select Department Information to continue');

//                return false;
//            }
//        }

        if (($user = $this->user->byUsernameOrEmail($input['email']))
            || ($user = $this->user->byUsernameOrEmail($input['username']))
        ) {
            $this->notify('info', 'This User already exists, please see the details below');

            return $user;
        }

        if (!$this->valid($input)) {
            $this->errors = $this->validator->errors();

            $this->notify('error', 'We there was some issues with the data, please verify');

            return false;
        }

        $user = $this->user->create($input, $input['udls']);

        if (!$user) {
            $this->notify('error', 'Something strange happened, could not created User. try again later');

            return false;
        }

        if (!$user) {
            $this->notify('error', 'There was an issue creating this employee');

            return false;
        }

        $this->notify('success',
            'User Created. Please Verify the proper User and Path was created in EasyVista. If not, report an issue immediately in Teamwork');

        return true;
    }

    /**
     * Validate the  input.
     *
     * @param $input
     *
     * @return bool
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }

    /**
     * Get an User by Email.
     *
     * @param $email
     *
     * @return object of User
     */
    public function getUserByEmail($email)
    {
        $user = $this->user->byEmail($email);

        return $user;
    }

    /**
     * @param array $input
     *
     * @return bool
     */
    public function update(array $input, HelperController $helper = null)
    {
        if (!$this->valid($input)) {
            $this->notify('error', 'We there was some issues with the data, please verify');

            return false;
        }

        // generate new IDs
        if (isset($input['identificationRegenerate']) && $input['identificationRegenerate']) {
            $helper = $helper ?: app()->make('WA\Http\Controllers\Admin\HelperController');
            $input['identification'] = $helper->generateIds($input['companyId']);

            if (empty($input['companyUserIdentifier'])) {
                $input['companyUserIdentifier'] = $input['identification'];
            }
        }

        // there must be a value select or non-census clients
        if (!$input['isCensusCompany']) {
            $udl_counter = 0;
            foreach ($input['udls'] as $udls => $udl) {
                if ($b = array_pop($udl)['value'] != '') {
                    $udl_counter += 1;
                }
            }

            if ($udl_counter === 0) {
                $this->notify('error', 'You must select Department/UDL Information to continue');

                return false;
            }
        }

        $user = $this->user->update($input, $input['udls']);

        if (!$user) {
            $this->notify('error', 'Something strange happened, could not create User. try again later');

            return false;
        }

        // Send the employee to EasyVista help desk
      /*  if (!$this->updateEasyVista(['employee' => $user, 'input' => $input])) {
            $this->notify('error', $this->helpDeskApi->getError());

            return false;
        }*/

        $this->notify('success', 'User Updated!');

        return $user;
    }

    //from EasyVista

    /**
     * @param array $data
     *
     * @return bool
     */
    public function updateEasyVista(array $data = [])
    {
        $this->helpDeskApi->connect();

        $externalId = $data['employee']->identification;
        // Any other  updates:
        // the identification is the EV ID
        $lastName = $data['input']['lastName'];
        $firstName = $data['input']['firstName'];
        $name = $lastName.', '.$firstName;
        $data['input']['approverId'] = empty($data['input']['approverId']) ? 0 : $data['input']['approverId'];

        $userInfo = array(
            'name' => $name,
            'identification' => $externalId,
        );

        $xmlString = <<<XML

       <fields>
         <field name="last_name" xsi:type="xsd:string">{$data['input']['lastName']}, {$data['input']['firstName']}</field>
         <field name="identification" xsi:type="xsd:string">{$externalId}</field>
         <field name="location_id" xsi:type="xsd:string">{$data['input']['defaultLocationId']}</field>
        <field name="language_id" xsi:type="xsd:string">1</field>
         <field name="vip_level_id" xsi:type="xsd:string">{$data['input']['level']}</field>
         <field name="default_domain_id" xsi:type="xsd:string"></field>
         <field name="manager_id" xsi:type="xsd:string">{$data['input']['supervisorId']}</field>
          <field name="validator_id" xsi:type="xsd:string">{$data['input']['approverId']}</field>
          <field name="notification_type_id" xsi:type="xsd:string">{$data['input']['notify']}</field>
         <field name="department_id" xsi:type="xsd:string">{$data['input']['evDepartmentId']}</field>
         <field name="e_mail" xsi:type="xsd:string">{$data['input']['email']}</field>
           <field name="approved_to_validate" xsi:type="xsd:string">{$data['input']['isValidator']}</field>
        <field name="COMMENT_EMPLOYEE"><![CDATA[{$data['input']['notes']}]]></field>
         <field name="impact" xsi:type="xsd:string"></field>
         </fields>
XML;

        if (!$response = $this->helpDeskApi->updateUser($xmlString, $userInfo)) {
            Log::error($xmlString.' >> '.$response);

            return false;
        }

        return true;
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function edit($id)
    {
        return $this->user->byId($id);
    }

    /**
     * Get errors on the validation.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * @param null $companyId
     *
     * @return object
     *
     * @throws \Exception
     */
//    public function getSupervisors($companyId = null)
//    {
//        if ($this->currentCompany == null) {
//            throw new \Exception('No Company is set, please select a company');
//        }

//        $companyId = $companyId ?: $this->currentCompany->id;

//        return $sups = $this->helpDesk->getSupervisors($companyId);

////        return $sups = $this->user->getAllSupervisors($companyId);
//    }

    /**
     * @param null $companyId
     *
     * @return object
     */
//    public function getValidator($companyId = null)
//    {
//        $companyId = $companyId ?: $this->currentCompany->id;

//        return $this->helpDesk->getValidators($companyId);
////        return $this->user->getAllValidators($companyId);
//    }

    /**
     * @param $id
     *
     * @return object
     */
    public function show($id)
    {
        return $this->user->byId($id);
    }

    /**
     * Get the current datastore object.
     *
     * @return \WA\DataStore\Company\Company
     */
    public function getCurrentCompany()
    {
        return $this->currentCompany;
    }

    public function getCompanies()
    {
        return $this->session->get('companies');
    }

    /**
     * Get a company's UDL and it's values.
     *
     * @param bool $udlValues
     *
     * @return array
     */
    public function getUdls($udlValues = true)
    {
        $companyId = $this->currentCompany['id'];

        if (empty($companyId)) {
            return [];
        }

        $udls = $this->udl->byCompanyId($companyId, $udlValues);

        return $udls;
    }

    /**
     * Returns the path ID given the udl path.
     *
     * @param array $udls       of udl and values
     * @param int   $creatorId  current user id
     * @param array $userInfo   of user being created/edited
     * @param bool  $externalId should be return or not
     * @param int   $companyId  of the company
     *
     * @return int of the department path ID
     */
    public function getDepartmentPathId(
        array $udls,
        $creatorId,
        array $userInfo,
        $externalId = false,
        $companyId = null
    ) {
        if (empty($companyId = $companyId ?: $this->currentCompany['id']) || empty($udls)) {
            return null;
        }

        $path = $this->company->getUdlValuePathId($companyId, $udls, $externalId, $creatorId, $userInfo);

        return $path;
    }

    /**
     * Get an employee's UDL and it's values.
     *
     * @param $id
     *
     * @return array
     */
    public function getUserUdls($id)
    {
        if (!empty($id)) {
            $udls = $this->user->getUdls($id);

            return $udls;
        }
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function updateEasyVistaUsername(array $data)
    {
        $this->helpDeskApi->connect();

        $id = $data['employee']->id + 20000;

        //TODO: in production it should read:
        //$id = $data['employee']->id ;

        //Any other  updates:
        $xmlString = <<<XML
                          <field name="LAST_NAME">{$data['input']['lastName']}, {$data['input']['firstName']}</field>

XML;

        if (!$response = $this->helpDeskApi->updateUser($xmlString)) {
            Log::error($xmlString.' >> '.$response);

            return false;
        }

        return true;
    }

    /**
     * Delete an employee.
     *
     * @param int $id of the emplpyee
     *
     * @return bool
     */
    public function delete($id)
    {
        $user = $this->user->byId($id);

        $this->setLimits();
        if (!$this->user->delete($id)) {
            $this->notify('error', 'Could not delete this employee, please try again later');

            return false;
        }
        $this->notify('success', "$user->identification Deleted");

        return $this->user->delete($id);
    }

    public function getUserPermissions($id)
    {
        $permissions = [];
        $roles = $this->user->getRoles($id);
        if (!empty($roles)) {
            foreach ($roles as $role) {
                $permissions[] = $this->role->getPermissions($role->id);
            }
        }

        return $permissions;
    }

    /**
     * Get all available roles.
     *
     * @return array
     */
    public function getAllRoles()
    {
        return $this->role->getAllRoles();
    }

    /**
     * Get User roles by Id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getUserRoles($id)
    {
        return $this->user->getRoles($id);
    }

    /**
     * Get email domains available for a company.
     *
     * @param $companyId
     *
     * @return array
     */
    public function getEmailDomains($companyId)
    {
        $email_domains = $this->company->getDomains($companyId);

        return $email_domains;
    }

    /**
     * Get current charges of user for current billing month.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getCurrentCharges($id)
    {
        $user = $this->user->byId($id);
        $email = $user->email;
        if (!empty($email)) {
            return $this->allocations->getCurrentCharges($email);
        }

        return false;
    }
}
