<?php

namespace WA\Services\Form\Company;

use WA\Repositories\Company\CompanyInterface;
use WA\Repositories\UdlValue\UdlValueInterface;
use WA\Services\Form\AbstractForm;
use WA\Helpers\Traits\SetLimits;

/**
 * Class CompanyForm.
 */
class CompanyForm extends AbstractForm
{
    use SetLimits;

    protected $company;

    protected $udlValue;

    /**
     * @var CompanyFormValidator
     */
    protected $validator;

    /**
     * @param CompanyInterface  $company
     * @param UdlValueInterface $udlValue
     * @param CompanyFormValidator $validator
     */
    public function __construct(
        CompanyInterface $company,
        UdlValueInterface $udlValue,
        CompanyFormValidator $validator
    ) {
        $this->company = $company;
        $this->udlValue = $udlValue;
        $this->validator = $validator;
    }

    /**
     * @param $id
     *
     * @return Object
     */
    public function getCompanyById($id)
    {
        return $this->company->byId($id);
    }

    /**
     * Get a companies UDL (and Values) by it's Id.
     *
     * @param $id
     *
     * @return array of UDLs
     */
    public function getCompanyUdls($id)
    {
        return $this->company->getUDLs($id);
    }

    /**
     * Get the details a company UDL.
     *
     * @param int    $companyId
     * @param string $udlName
     *
     * @return Object of UDL Information
     */
    public function getUdlByName($companyId, $udlName)
    {
        $udl = $this->udlValue->byName($udlName, $companyId);
//        $udlUserCount

        return [
            'name' => 'Something',
            'employeeCount' => 209,
        ];
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
     * Get errors on the validation.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Get a Company by Name.
     *
     * @param $name
     *
     * @return object of Company
     */
    public function getCompanyByName($name)
    {
        $company = $this->company->byName($name);
        return $company;
    }

    /**
     * @param array $input
     *
     * @return bool|object of company data
     */
    public function create(array $input)
    {
        if ($company = $this->company->byName($input['name'])) {
            $this->notify('info', 'A company by this name already exists, please see the details below');

            return $company;
        }

        if (!$this->valid($input)) {
            $this->errors = $this->validator->errors();
            $this->notify('error', 'There was some issue with the data, please verify');

            return false;
        }

        //Carriers BAN cannot be null
        if (isset($input['carrierId']) && count($input['carrierId']) >= 1) {
            for ($x = 0; $x < count($input['carrierId']); ++$x) {
                if (!empty($input['carrierId'][$x]) && empty($input['carrierBAN'][$x])) {
                    $this->notify('error', 'BillingAccountNumber cannot be null for Carriers. Please try again');
                    return false;
                }
            }
        }

        $company = $this->company->create($input);

        if (!$company) {
            $this->notify('error', 'There was an issue creating this Company. Please try again later');
            return false;
        }

        $this->notify('success', 'Company Created Successfully');
        return true;
    }

    /**
     * Delete a Company By Id.
     *
     * @param int $id Company Id
     *
     * @return bool
     */
    public function delete($id)
    {
        $company = $this->company->byId($id);

        $this->setLimits();
        if (!$this->company->delete($id)) {
            $this->notify('error', 'Could not delete this Company, please try again');
            return false;
        }
        $this->notify('success', " Company $company->name Deleted");

        return $this->company->delete($id);
    }

    /**
     * @param array $input
     *
     * @return bool
     */
    public function update(array $input)
    {
        if (!$this->valid($input)) {
            $this->notify('error', 'There are some issues with the data, please verify');
            return false;
        }

        $company = $this->company->update($input);

        if (!$company) {
            $this->notify('error', 'Could not save company details. Please try again later');

            return false;
        }

        $this->notify('success', " Company $company->name Updated Successfully");

        return $company;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getCompanyPools($id)
    {
        return $this->company->getPools($id);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getCompanyCarriers($id)
    {
        return $this->company->getCompanySpecific($id);
    }
}
