<?php

namespace WA\DataStore\User;

use League\Fractal\Resource\Collection as ResourceCollection;
use WA\DataStore\Allocation\AllocationTransformer;
use WA\DataStore\FilterableTransformer;
use WA\DataStore\UdlValue\UdlValueTransformer;

/**
 * Class UserTransformer.
 */
class UserTransformer extends FilterableTransformer
{
    protected $availableIncludes = [
        'assets',
        'services',
        'companies',
        'orders',
        'devicevariations',
        'roles',
        'allocations',
        'contents',
        'udls',
        'addresses'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'                    => $user->id,
            'uuid'                  => $user->uuid,
            'identification'        => $user->identification,
            'email'                 => $user->email,
            'alternateEmail'        => $user->alternateEmail,
            'password'              => $user->password,
            'username'              => $user->username,
            'confirmation_code'     => $user->confirmation_code,
            'remember_token'        => $user->remember_token,
            'confirmed'             => $user->confirmed,
            'firstName'             => $user->firstName,
            'lastName'              => $user->lastName,
            'alternateFirstName'    => $user->alternateFirstName,
            'supervisorEmail'       => $user->supervisorEmail,
            'companyUserIdentifier' => $user->companyUserIdentifier,
            'isSupervisor'          => $user->isSupervisor,
            'isValidator'           => $user->isValidator,
            'isActive'              => $user->isActive,
            'rgt'                   => $user->rgt,
            'lft'                   => $user->lft,
            'hierarchy'             => $user->hierarchy,
            'defaultLang'           => $user->defaultLang,
            'notes'                 => $user->notes,
            'level'                 => $user->level,
            'notify'                => $user->notify,
            'companyId'             => $user->companyId,
            'syncId'                => $user->syncId,
            'supervisorId'          => $user->supervisorId,
            'externalId'            => $user->externalId,
            'approverId'            => $user->approverId,
            'defaultLocationId'     => $user->defaultLocationId,
            'deleted_at'            => $user->deleted_at,
            'created_at'            => $user->created_at,
            'updated_at'            => $user->updated_at
        ];
    }


    /**
     * @param User $user
     *
     * @return ResourceCollection Allocations
     */
    public function includeAllocations(User $user)
    {
        $this->criteria = $this->getRequestCriteria();
        $allocations = $this->applyCriteria($user->allocations(), $this->criteria, true,
            ['allocations' => 'allocations']);
        $filters = $this->criteria['filters']->get();

        // Get the last (x) allocations for all carriers
        $monthFilters = preg_grep('/\[allocations.billMonth\]\=\[currentBillMonths.last:(\d+)?\]/', $filters);
        if ($monthFilters) {
            $take = $this->checkForMonthFilters($monthFilters);
            $months = $user->companies->currentBillMonths;
            $allocations->where(function ($query) use ($months, $take) {
                foreach ($months as $month) {
                    $query->orWhere(function ($q) use ($month, $take) {
                        $q->where('carrier', $month->carrierId);
                        $q->where('billMonth', 'BETWEEN',
                            \DB::raw('DATE("' . $month->currentBillMonth . '" - INTERVAL ' . ($take - 1) . ' MONTH) AND DATE("' . $month->currentBillMonth . '")'));
                        $q->take($take);
                    });
                }
            });
        }

        // Get the most recent allocation for the current company bill month (deprecating)
        if (in_array('[allocations.billMonth]=[company.currentBillMonth]', $filters)) {
            $allocations->where('billMonth', $user->companies->currentBillMonth);
        }

        return new ResourceCollection($allocations->get(), new AllocationTransformer(), 'allocations');
    }

    /**
     * @param $monthFilters
     * @return int default to 1
     */
    protected function checkForMonthFilters($monthFilters)
    {
        foreach ($monthFilters as $str) {
            if (preg_match('/^\[allocations.billMonth\]\=\[currentBillMonths.last:(\d+)?\]/', $str, $m)) {
                return $m[1];
            }
        }
        return 1;
    }

    /**
     * Dynamic include override because of mixed case
     *
     * @param User $user
     *
     * @return ResourceCollection Contents
     */
    public function includeUdls(User $user)
    {
        $this->criteria = $this->getRequestCriteria();
        $udlValues = $this->applyCriteria($user->udlValues(), $this->criteria);
        return new ResourceCollection($udlValues->get(), new UdlValueTransformer(), 'udlvalues');
    }
}
