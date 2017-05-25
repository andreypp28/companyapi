<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Company\Company;
use WA\DataStore\Company\CompanyTransformer;
use WA\DataStore\Udl\Udl;
use WA\DataStore\Udl\UdlTransformer;
use WA\Repositories\Company\CompanyInterface;
use WA\Repositories\Udl\UdlInterface;
use DB;
use Log;

use WA\DataStore\Company\CompanyUserImportJob;
use WA\Helpers\Vendors\CSVParser;
use Illuminate\Support\Facades\Auth;
use WA\Jobs\ImportBulkUsersJob;
use Illuminate\Support\Facades\Queue;

/**
 * Class CompaniesController.
 */
class CompaniesController extends FilteredApiController
{
    /**
     * @var CompanyInterface
     */
    protected $company;

    /**
     * @var CarrierInterface
     */
    protected $carrier;

    /**
     * @var UdlInterface
     */
    protected $udl;

    /**
     * CompaniesController constructor.
     *
     * @param CompanyInterface $company
     * @param CarrierInterface $carrier
     * @param UdlInterface $udl
     * @param Request $request
     */
    public function __construct(
        CompanyInterface $company,
        UdlInterface $udl,
        Request $request
    ) {
        parent::__construct($company, $request);
        $this->company = $company;
        $this->udl = $udl;
    }

    /**
     * Update a company.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        $success = true;

        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'companies')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');

            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        try {
            $data = $request->all()['data'];
            $data['attributes']['id'] = $id;
            $company = $this->company->update($data['attributes']);

            if ($company == 'notExist') {
                DB::rollBack();
                $error['errors']['company'] = Lang::get('messages.NotExistClass', ['class' => 'Company']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['notexists']);
            }

            if ($company == 'notSaved') {
                DB::rollBack();
                $error['errors']['company'] = Lang::get('messages.NotSavedClass', ['class' => 'Company']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['company'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Company', 'option' => 'updated', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships']) && $success) {
            $dataRelationships = $data['relationships'];

            if (isset($dataRelationships['addresses'])) {
                if (isset($dataRelationships['addresses']['data'])) {
                        
                    $addressInterface = app()->make('WA\Repositories\Address\AddressInterface');
                    $data = $dataRelationships['addresses']['data'];

                    $addressIdArray = [];

                    foreach ($data as $item) {
                        try {
                            if($item['id'] > 0) {
                                array_push($addressIdArray, $item);
                            } else {
                                $newAddress = $addressInterface->create($item['attributes']);
                                Log::debug("NEW ADDRESS: ". print_r($newAddress, true));
                                $aux['id'] = $newAddress->id;
                                $aux['type'] = 'addresses';
                                array_push($addressIdArray, $aux);
                            }

                            $dataAddress = $this->parseJsonToArray($addressIdArray, 'addresses');
                            $company->addresses()->sync($dataAddress);                                
                        } catch (\Exception $e) {
                            DB::rollBack();
                            $error['errors']['addresses'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'Company', 'option' => 'created', 'include' => 'Address']);
                            $error['errors']['Message'] = $e->getMessage();
                            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                        }
                    }
                }
            }

            if (isset($dataRelationships['udls'])) {
                if (isset($dataRelationships['udls']['data'])) {

                    try {
                        $udl = Udl::where('companyId', $company->id)->get();

                        $udlInterface = app()->make('WA\Repositories\Udl\UdlInterface');
                        $this->deleteNotRequested($dataRelationships['udls']['data'], $udl, $udlInterface, 'udls');

                        $helper = app()->make('WA\Http\Controllers\UdlsHelperController');
                        $success = $helper->create($dataRelationships['udls'], $company->id);

                        if (!$success){
                            $error['errors']['udls'] = Lang::get('messages.NotOptionIncludeClass',['class' => 'Udl', 'option' => 'updated', 'include' => '']);
                        }

                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['udls'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'Udl', 'option' => 'updated', 'include' => '']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }
        }

        if ($success) {
            DB::commit();
            return $this->response()->item($company, new CompanyTransformer(),
                ['key' => 'companies'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
    /**
     * Create a new company.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {   
        $success = true;
       
        if (!$this->isJsonCorrect($request, 'companies')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
        else{
            $data = $request->all()['data'];
        }

        DB::beginTransaction();

        try {
            $company = $this->company->create($data['attributes']);
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['companies'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'Company', 'option' => 'created', 'include' => '']);
            $error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        /*
         * Check if Json has relationships to continue or if not and commit + return.
         */
        if (isset($data['relationships']) && $success) {
            $dataRelationships = $data['relationships'];

            if (isset($dataRelationships['addresses'])) {
                if (isset($dataRelationships['addresses']['data'])) {
                        
                    $addressInterface = app()->make('WA\Repositories\Address\AddressInterface');
                    $data = $dataRelationships['addresses']['data'];

                    $addressIdArray = [];

                    foreach ($data as $item) {
                        try {
                            Log::debug("item: ". print_r($item, true));
                            if($item['id'] > 0) {
                                array_push($addressIdArray, $item);
                            } else {
                                $newAddress = $addressInterface->create($item['attributes']);
                                Log::debug("NEW ADDRESS: ". print_r($newAddress, true));
                                $aux['id'] = $newAddress->id;
                                $aux['type'] = 'addresses';
                                array_push($addressIdArray, $aux);
                            }

                            $dataAddress = $this->parseJsonToArray($addressIdArray, 'addresses');
                            $company->addresses()->sync($dataAddress);                                
                        } catch (\Exception $e) {
                            DB::rollBack();
                            $error['errors']['addresses'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'Company', 'option' => 'created', 'include' => 'Address']);
                            $error['errors']['Message'] = $e->getMessage();
                            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                        }
                    }
                }
            }

            if (isset($dataRelationships['udls'])) {
                if (isset($dataRelationships['udls']['data'])) {

                    try {

                        $helper = app()->make('WA\Http\Controllers\UdlsHelperController');
                        $success = $helper->create($dataRelationships['udls'], $company->id);

                        if (!$success){
                            $error['errors']['udls'] = Lang::get('messages.NotOptionIncludeClass',['class' => 'Udl', 'option' => 'created', 'include' => '']);
                        }

                    } catch (\Exception $e) {
                        $success = false;
                        $error['errors']['udls'] = Lang::get('messages.NotOptionIncludeClass', ['class' => 'Udl', 'option' => 'created', 'include' => '']);
                        //$error['errors']['Message'] = $e->getMessage();
                    }
                }
            }
        }

        if($success){
            DB::commit();
            return $this->response()->item($company, new CompanyTransformer(),
                ['key' => 'companies'])->setStatusCode($this->status_codes['created']);
        } else {
            DB::rollBack();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
    

    /**
     * Delete a company.
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteCompany($id)
    {
        $company = Company::find($id);
        if ($company != null) {
            $this->company->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Company']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $company = Company::find($id);
        if ($company == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Company']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }

    /**
     * Create a job and return the details.
     *
     * @param $companyId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jobs($companyId, Request $request) {
        $company = Company::find($companyId);
        if($company == null) {
            $error['errors']['company'] = Lang::get('messages.NotExistClass', ['class' => 'Company']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $subFolder = str_slug($company->name);
        $storagePath = 'clients' . DIRECTORY_SEPARATOR . $subFolder;
        $file = $request->file('csv');
        $fileName = null;
        $fileExtension = null;

        // check if files is exist
        if (is_null($file)) {
            $error['errors']['csv'] = Lang::get('messages.NotExistFile');

            return response()->json($error)->setStatusCode($this->status_codes['badrequest']);
        }

        // check if file extension is csv
        $originalFileName = $file->getClientOriginalName();
        $fileName = pathinfo($originalFileName, PATHINFO_FILENAME) . time();
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        if ($fileExtension !== 'csv') {
            $error['errors']['file'] = Lang::get('messages.NotRightFile', ['class' => 'csv']);

            return response()->json($error)->setStatusCode($this->status_codes['badrequest']);
        }

        // move file to storage
        $uploadedFileName = str_slug($fileName) . '.' . $fileExtension;
        if(!($file = $file->move(storage_path($storagePath), $uploadedFileName))) {
            $error['errors']['file'] = Lang::get('messages.NotExistPath');

            return response()->json($error)->setStatusCode($this->status_codes['forbidden']);
        }

        $filePath = $file->getRealPath();
        $csvParser = new CSVParser($filePath);
        $rows = $csvParser->getRows(true);

        // check file is empty
        if(count($rows) == 0) {
            $error['errors']['file'] = Lang::get('messages.EmptyFile');

            return response()->json($error)->setStatusCode($this->status_codes['badrequest']);
        }

        // create import job
        //$randomRow = count($rows) > 1 ? $rows[mt_rand(1, count($rows)-1)] : array();
        $randomRow = count($rows) > 1 ? $rows[1] : array();
        $sampleRow = $this->getSampleImportRow($rows[0], $randomRow);

        $job = new CompanyUserImportJob;
        $job->company_id = $company->id;
        $job->path = $storagePath;
        $job->file = $uploadedFileName;
        $job->total     = count($rows) - 1;
        $job->created   = 0;
        $job->updated   = 0;
        $job->failed    = 0;
        $job->fields    = serialize($rows[0]);
        $job->sample    = serialize($sampleRow);
        $job->mappings  = serialize(array());
        $job->status    = CompanyUserImportJob::STATUS_PENDING;
        $job->created_by_id = Auth::id();
        $job->updated_by_id = Auth::id();
        $job->save();

        return response()->json($job->getJobData())->setStatusCode($this->status_codes['created']);
    }

    /**
     * Retrieve the information related to the job.
     *
     * @param $companyId
     * @param $jobId
     * @return mixed
     */
    public function job($companyId, $jobId) {
        // check if company is exist
        $company = Company::find($companyId);
        if($company == null) {
            $error['errors']['company'] = Lang::get('messages.NotExistClass', ['class' => 'Company']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        // get job details
        $job = CompanyUserImportJob::find($jobId);
        if($job == null) {
            $error['errors']['job'] = Lang::get('messages.NotExistClass', ['class' => 'Job']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        return response()->json($job->getJobData());
    }

    /**
     * Update the information related to the job.
     *
     * @param $companyId
     * @param $jobId
     * @param Request $request
     * @return mixed
     */
    public function updateJob($companyId, $jobId, Request $request) {
        if (!$this->isJsonCorrect($request, 'jobs')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        // check if company is exist
        $company = Company::find($companyId);
        if($company == null) {
            $error['errors']['company'] = Lang::get('messages.NotExistClass', ['class' => 'Company']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        // get job details
        $job = CompanyUserImportJob::find($jobId);
        if($job == null) {
            $error['errors']['job'] = Lang::get('messages.NotExistClass', ['class' => 'Job']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        // request data
        // check mapping is array and not empty
        $data = $request->get('data');
        if(!isset($data['attributes'])
            || !isset($data['attributes']['mappings'])
            || !is_array($data['attributes']['mappings'])
            || empty($data['attributes']['mappings'])) {
            $error['errors']['job'] = Lang::get('messages.InvalidJson');
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
        $job->mappings = serialize($data['attributes']['mappings']);
        $job->save();

        // start importing
        dispatch(new ImportBulkUsersJob($jobId));
        //Queue::push(new ImportBulkUsersJob);

        return response()->json($job->getJobData(true));
    }

    private function getSampleImportRow($header, $row) {
        $result = new \stdClass();
        foreach($header as $index => $field) {
            if(isset($row[$index])) {
                $result->$field = $row[$index];
            } else {
                $result->$field = '';
            }
        }

        return $result;
    }

}
