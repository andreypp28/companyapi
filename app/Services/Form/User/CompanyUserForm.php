<?php

namespace WA\Services\Form\User;

use Illuminate\Session\SessionManager as Session;
use Mail;
use WA\DataStore\Company\Company;
use WA\DataStore\User\User;
use WA\Repositories\Company\CompanyInterface;
use WA\Repositories\User\UserInterface;
use WA\Repositories\HelpDesk\HelpDeskInterface;
use WA\Services\Form\AbstractForm;
use WA\Services\Soap\HelpDeskEasyVista as HelpDeskApi;

/**
 * Class UserForm.
 */
class CompanyUserForm extends AbstractForm
{
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
     * @var CompanyInterface
     */
    protected $company;

    public function __construct(
        UserInterface $user,
        CompanyUserFormValidator $validator,
        CompanyInterface $company

    ) {
        $this->validator = $validator;
        $this->user = $user;
        $this->company = $company;
    }

    /**
     * @param array $input
     *
     * @return bool|object of employee data
     */
    public function create(array $input)
    {
        if (!$this->isValidCompanyDomain($input['email'])) {
            $this->notify('error',
                'The email address you have entered is not a valid domain for your organization. Please enter your company-standard email address and try again.');

            return false;
        }

        $email = isset($input['email']) ? $input['email'] : null;
        if (!empty($email)) {
            $user = $this->user->byEmail($email);
            if (!empty($user)) {
                $this->notify('error', 'There is an existing employee with the given email. Please try another email address.');

                return false;
            }
        }

        $user = $this->user->create($input, []);

        if (!$user) {
            $this->notify('error', 'Something strange happened, could not created User. try again later');

            return false;
        }

        if (!$user) {
            $this->notify('error', 'There was an issue creating this employee');

            return false;
        }

        $this->notify('success',
            'Account Successfully created, please check your email and try to login');

        return true;
    }

    /**
     * Gets the company ID by the email.
     *
     * @param string $email
     *
     * @return int
     */
    public function getCompanyIdByEmail($email)
    {
        return $this->company->getIdByUserEmail($email);
    }

    /**
     * Check if this is a valid company domain.
     *
     * @param string $email
     *
     * @return bool
     */
    protected function isValidCompanyDomain($email)
    {
        $companyId = $this->company->getIdByUserEmail($email);

        return (bool) $companyId;
    }

    /**
     * Send the confirmation email for login info.
     *
     * @param User  $user
     * @param array $optional []
     *
     * @return bool
     */
    public function sendLoginDetails(User $user, $optional = [])
    {
        $redirect_base = route('login');
        $redirectPath = ((bool) $optional['legacyDestination']) ? $optional['legacyDestination'] : $redirect_base;
//        $appName = ((bool)$optional['isLegacy']) ? "CLEAN Platform" : "CLEAN Platform";
        $appName = 'Wireless Analytics';

        $data = [
            'email' => $user->email,
            'password' => isset($optional['password']) ? $optional['password'] : '',
            'companyName' => ucfirst($this->company->byId($user->companyId)->name),
            'redirectPath' => $redirectPath,
            'appName' => $appName,
        ];

        $mail = Mail::send('emails.employee.welcome', $data, function ($m) use ($user, $appName) {
            $m->from(env('MAIL_FROM_ADDRESS'), 'Wireless Analytics');
            $m->to($user->email)->subject('Welcome to '.$appName.' !');
        });

        return true;
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
     * Get the company by it's id.
     *
     * @param int $id
     *
     * @return Company
     */
    public function getCompany($id)
    {
        return $this->company->byId($id);
    }

    /**
     * Returns the path ID given the udl path.
     *
     * @param int  $companyId  of the company
     * @param bool $externalId should be return or not
     *
     * @return int of the department path ID
     */
    public function getDepartmentPathId(
        $companyId,
        $externalId = false
    ) {
        $path = $this->company->getUdlValuePathId($companyId, [], $externalId, null, []);

        return $path;
    }
}
