<?php

namespace WA\Services\Form\Company;

use Schema;
use DB;
use WA\DataLoader\DataParserInterface;
use WA\Helpers\Traits\SetLimits;
use WA\Repositories\Census\CensusInterface;
use WA\Repositories\Company\CompanyInterface;
use WA\Repositories\JobStatus\JobStatusInterface;
use WA\Services\Form\AbstractForm;
use WA\Services\Form\Census\DataMapForm;
use WA\Parsers\Census\Census;

class CensusForm extends AbstractForm
{
    use SetLimits;

    /**
     * @var CompanyInterface
     */
    protected $company;

    /**
     * @var DataParserInterface
     */
    protected $parser;

    /**
     * @var Census
     */
    protected $census;

    /**
     * @param CensusFormValidator $validator
     * @param CompanyInterface    $company
     * @param DataParserInterface $parser
     * @param Census              $census
     */
    public function __construct(
        CensusFormValidator $validator,
        CompanyInterface $company,
        DataParserInterface $parser,
        Census $census
    ) {
        $this->company = $company;
        $this->parser = $parser;
        $this->census = $census;
    }

    /**
     * Check if there is mapping for the current company,.
     *
     * @param $companyId
     *
     * @return bool
     */
    public function hasMapping($companyId)
    {
        $censusMapping = (bool) $this->company->getDataHeader($companyId, 'census');

        if (!$censusMapping) {
            $this->notify('error', 'No census data mapping. Create one now');
        }

        return $censusMapping;
    }

    /**
     * Create a new census.
     *
     * @param array              $input
     * @param CensusInterface    $census | null
     * @param JobStatusInterface $status | null
     *
     * @return int of the census if it's successful
     */
    public function createCensus(array $input, CensusInterface $census = null, JobStatusInterface $status = null)
    {
        $census = $census ?: app()->make('WA\Repositories\Census\CensusInterface');
        $status = $status ?: app()->make('WA\Repositories\JobStatus\JobStatusInterface');

        $statusId = $status->idByName('loading');

        $cache_table_name = $this->getCachedTableName($input['companyId']);

        $file_count = DB::table($cache_table_name)->count();

        $newCensus = $census->create([
            'companyId' => $input['companyId'],
            'statusId' => $statusId,
            'rawFileLineCount' => $file_count,
            'file' => $input['file'],
        ]);

        return (int) $newCensus['id'];
    }

    /**
     * Save the new file to the database
     * via various updates.
     *
     * @param array $input
     *
     * @return bool
     */
    public function save(array $input)
    {
        $version_id = $this->company->getMapVersion($input['companyId'], 'census');
        $mapping = isset($input['mapping']) ? $input['mapping'] : null;
        $file = isset($input['file']) ? $input['file'] : null;

        if (is_null($file) || is_null($mapping)) {
            $error_msg = 'No file or mapping not found, cannot continue';
            $this->notify('error', $error_msg);

            return false;
        }

        if (!$this->census->setUp($file, $version_id)) {
            $error_msg = 'Could not doing all the prepping to load the file, try later';
            $this->notify('error', $error_msg);

            return false;
        }

        return true;
    }

    /**
     * @param int              $companyId
     * @param DataMapForm|null $dataMap
     *
     * @return object
     */
    public function getMapping($companyId, DataMapForm $dataMap = null)
    {
        $dataMap = $dataMap ?: app()->make('WA\Services\Form\Census\DataMapForm');

        return $dataMap->getMappingById($companyId);
    }

    /**
     * Load the needed file, based on this parser.
     *
     * @param       $filePath
     * @param array $columns
     * @param int   $take
     *
     * @return object of loaded file
     */
    protected function loadFile($filePath, array $columns, $take = 0)
    {
        $parser = $this->parser->getDataParserInstance();

        $read = $parser->load($filePath);

        if (!empty($columns)) {
            $columns = array_map(
                function ($c) {
                    return str_replace(' ', '_', strtolower($c));
                },
                $columns
            );

            return $read->get($columns);
        }

        return (!(bool) $take) ? $read->all() : $read->take($take);
    }

    public function getDepartmentPathId($companyId)
    {
        //@FIXME: return the real UDL Path a la the web interface
        return 0;
    }

    /**
     * Get a single census.
     *
     * @param int             $id
     * @param CensusInterface $census
     */
    public function getCensus($id, CensusInterface $census = null)
    {
        $census = $census ?: app()->make('WA\Repositories\Census\CensusInterface');

        return $census->byId($id);
    }

    /**
     * Get the rules that will be run.
     *
     * @param $companyId
     *
     * @return mixed
     */
    public function getRules($companyId)
    {
        return $this->census->getRules($companyId);
    }

    /**
     * Run all the census process, based on defined rules.
     *
     * @param $companyId
     * @param $censusId
     *
     * @throws \Exception
     */
    public function processCensus($companyId, $censusId)
    {
        $this->census->process($companyId, $censusId);
    }

    /**
     * Get the summary of the job tha will be done before it's processed.
     *
     * @param int                  $companyId
     * @param int                  $censusId
     * @param CensusInterface|null $census
     *
     * @return array of summaries
     */
    public function getPreProcessSummaries($companyId, $censusId, CensusInterface $census = null)
    {
        $census = $census ?: app()->make('WA\Repositories\Census\CensusInterface');
        $summaries = [];

        // Quick Quality Checks -
        // a. Get the total rows that exist in this census vs. previously loaded census
        $summaries['previousCensus'] = $census->byCompany($companyId, true);
        $summaries['currentCensus'] = $census->byId($censusId);

        // Get a summaries of any errors that might be in the files
        $summaries['preProcess'] = ['employeesCount' => $this->company->getUsersCount($companyId)];

        return $summaries;
    }

    /**
     * Get the table name used to save the CSV.
     *
     * @param $companyId
     *
     * @return string | null
     */
    private function getCachedTableName($companyId)
    {
        $table = $this->getMapping($companyId)['versionId'];

        if (!Schema::hasTable($table)) {
            return null;
        }

        return $table;
    }

    /**
     * @param $companyId
     *
     * @return string company name
     */
    public function getCompanyName($companyId)
    {
        return $this->company->byId($companyId)->name;
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function getLogsCount($id, CensusInterface $census = null)
    {
        $census = $census ?: app()->make('WA\Repositories\Census\CensusInterface');

        $count = $census->getLogsCount($id);

        return $count;
    }
}
