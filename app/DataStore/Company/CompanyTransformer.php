<?php

namespace WA\DataStore\Company;

use League\Fractal\Resource\Collection as ResourceCollection;
use WA\DataStore\Allocation\AllocationTransformer;
use WA\DataStore\Content\ContentTransformer;
use WA\DataStore\Company\CompanyCurrentBillMonthTransformer;
use WA\DataStore\FilterableTransformer;
use WA\DataStore\Udl\UdlTransformer;

/**
 * Class CompanyTransformer.
 */
class CompanyTransformer extends FilterableTransformer
{

    protected $availableIncludes = [
        'allocations',
        'contents',
        'udls',
        'devicevariations',
        'currentBillMonths',
        'packages',
        'presets',
        'users',
        'addresses'
    ];

    /**
     * @param Company $company
     *
     * @return array
     */
    public function transform(Company $company)
    {
        return [
            'id'               => (int)$company->id,
            'name'             => $company->name,
            'label'            => $company->label,
            'active'           => (int)$company->active,
            'udlpath'          => $company->udlpath,
            'isCensus'         => (int)$company->isCensus,
            'udlPathRule'      => $company->udlPathRule,
            'assetPath'        => $company->assetPath,
            'shortName'        => $company->shortName,
            'currentBillMonth' => $company->currentBillMonth,
            'defaultLocation'  => $company->defaultLocation
        ];
    }

    /**
     * @param Company $company
     *
     * @return ResourceCollection Allocations
     */
    public function includeAllocations(Company $company)
    {
        $allocations = $this->applyCriteria($company->allocations(), $this->criteria);
        $filters = $this->criteria['filters']->get();

        if (in_array('[allocations.billMonth]=[company.currentBillMonth]', $filters)) {
            $allocations->where('billMonth', $company->currentBillMonth);
        }

        return new ResourceCollection($company->allocations, new AllocationTransformer(), 'allocations');
    }

    /**
     * @param Company $company
     *
     * @return ResourceCollection Contents
     */
    public function includeContents(Company $company)
    {
        $contents = $company->contents;

        if (count($contents) < 1) {
            //Return the default contents
            $contentInterface = app()->make('WA\Repositories\Content\ContentInterface');
            $defaultContents = $contentInterface->getDefaultContent();
            $contents = !empty($defaultContents) ? $defaultContents : $contents;
        }

        return new ResourceCollection($contents, new ContentTransformer(), 'contents');
    }

    /**
     * @param Company $company
     *
     * @return ResourceCollection CompanyCurrentBillMonth
     */
    public function includeCurrentBillMonths(Company $company)
    {
        return new ResourceCollection($company->currentBillMonths, new CompanyCurrentBillMonthTransformer(), 'currentBillMonths');
    }

    /**
     * @param Company $company
     *
     * @return ResourceCollection Contents
     */
    public function includeUdls(Company $company)
    {
        return new ResourceCollection($company->udls, new UdlTransformer(), 'udls');
    }
}
