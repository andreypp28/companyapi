<?php

namespace WA\Http\Controllers;

use App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use View;
use WA\Services\Form\Company\CompanyForm;
use Illuminate\Session\SessionManager as Session;
use WA\Repositories\PoolGroupRepositoryInterface;
use WA\Repositories\CarrierRepositoryInterface;

/**
 * Class CompaniesController.
 */
class CompaniesStaticController extends BaseController
{
    protected $companyForm;

    /**
     * @param CompanyForm                $companyForm
     * @param Session                    $session
     * @param CarrierRepositoryInterface $carriers
     */
    public function __construct(
        CompanyForm $companyForm,
        Session $session,
        CarrierRepositoryInterface $carriers)
    {
        $this->companyForm = $companyForm;
        $this->session = $session;
        $this->carriers = $carriers;
    }

    public function index()
    {
        // App::abort(404, 'Unknown URL');
        return View::make('companies.all');
    }

    /**
     * Show the summary of a specific company.
     *
     * @param $id
     */
    public function show($id)
    {
        $company = $this->companyForm->getCompanyById($id)->toArray();
        $udls = $this->companyForm->getCompanyUdls($id);

        $data = array_merge($this->data, $company, ['udls' => $udls]);

        return View::make('companies.show', $data);
    }

    /**
     * Show details of a udl.
     *
     * @param int    $id
     * @param string $udlName
     */
    public function showUdl($id, $udlName)
    {
        $udls = $this->companyForm->getUdlByName($id, $udlName);
        $data = array_merge($this->data, $udls);

        return View::make('companies.udl', $data);
    }

    /**
     * Create a New Company.
     */
    public function create()
    {
        $currentCompany = $this->session->get('clean.company');
        $poolGroups = $this->poolGroups->getAll();
        $carriers = $this->carriers->getActive();

        $data = array_merge(
            $this->data,
            [
                'currentCompany' => $currentCompany,
                'poolGroups' => $poolGroups,
                'carriers' => $carriers,
            ]);

        return View::make('companies.new', $data);
    }

    /**
     * Save Company.
     *
     * @return mixed
     */
    public function store()
    {
        $data['name'] = trim(Input::get('name'));
        $data['label']= !empty(Input::get('label')) ? trim(Input::get('label')) : trim(Input::get('name'));
        $data['shortName'] = !empty(Input::get('shortName')) ? Input::get('shortName') : "";
        $data['rawDataDirectoryPath'] =  !empty(Input::get('rawDataDirectoryPath')) ? Input::get('rawDataDirectoryPath') : "";
        $data['active'] =  (int)((bool)Input::get('active'));
        $data['isCensus'] = (int)((bool)Input::get('isCensus'));
        $data['carrierId'] =  !empty(Input::get('carrierId')) ? Input::get('carrierId') : 0;
        $data['carrierBAN'] =  !empty(Input::get('carrierBAN')) ? Input::get('carrierBAN') : null;
        $data['carrierPAN'] =  !empty(Input::get('carrierPAN')) ? Input::get('carrierPAN') : null;
        $data['poolGroupId'] =  !empty(Input::get('poolGroupId')) ? Input::get('poolGroupId') : 0;
        $data['poolBAN'] =  !empty(Input::get('poolBAN')) ? Input::get('poolBAN') : null;
        $data['baseCost'] =  !empty(Input::get('baseCost')) ? Input::get('baseCost') : -1;



        if (!$this->companyForm->create($data)) {
            $this->data['error'] = $this->companyForm->errors();

            return Redirect::back()->withInput()
                ->withInput(Input::except('carrierId', 'carrierBAN', 'carrierPAN', 'poolGroupId', 'poolBAN', 'baseCost'))
                ->withErrors($this->companyForm->errors());
        }

        $this->data['company'] = $this->companyForm->getCompanyByName($data['name']);
        $companyId = $this->data['company']['id'];

        return redirect("companies/$companyId")->with($this->data);
    }

    /**
     * Delete Company.
     *
     * @param CompanyId     @id
     */
    public function deleteCompany($id)
    {
        if (!$this->companyForm->delete($id)) {
            return Redirect::back();
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function edit($id)
    {
        $company = $this->companyForm->getCompanyById($id);

        $pools = $this->companyForm->getCompanyPools($id);
        $poolGroups = $this->poolGroups->getAll();
        $carriers = $this->carriers->getActive();
        $company_carriers = $this->companyForm->getCompanyCarriers($id);

        $data =
            [
                'company' => $company,
                'poolGroups' => $poolGroups,
                'carriers' => $carriers,
                'pools' => $pools,
                'company_carriers' => $company_carriers,
            ];

        return View::make('companies.edit')->with($data);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function update($id)
    {
        $data = [
            'id' => $id,
            'name' => trim(Input::get('name')),
            'label' => trim(Input::get('label')),
            'shortName' => trim(Input::get('shortName')),
            'active' => (int) ((bool) Input::get('active')),
            'isCensus' => (int) ((bool) Input::get('isCensus')),
            'poolGroupId' => Input::get('poolGroupId'),
            'baseCost' => Input::get('baseCost'),
            'poolBAN' => Input::get('poolBAN'),
            'carrierId' => Input::get('carrierId'),
            'carrierBAN' => Input::get('carrierBAN'),
            'carrierPAN' => Input::get('carrierPAN'),
        ];

        $updatedCompany = $this->companyForm->update($data);

        if (!$updatedCompany) {
            $this->data['errors'] = $this->companyForm->errors();
           // $this->data['company'] = $this->companyForm->show($data['id']);

            return $this->edit($data['id']);
        }

        $this->data['company'] = $updatedCompany;

        return Redirect::route('companies.edit', ['id' => $data['id']]);
    }
}
