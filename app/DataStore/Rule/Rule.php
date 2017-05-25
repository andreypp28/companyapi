<?php

namespace WA\DataStore\Rule;

use WA\DataStore\BaseDataStore;

class Rule extends BaseDataStore
{
    protected $table = 'system_rules';

    public function companies()
    {
        return $this->belongsToMany('WA\DataStore\Company\Company', 'company_rules', 'ruleId', 'companyId');
    }
}
