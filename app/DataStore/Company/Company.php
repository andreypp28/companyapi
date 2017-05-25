<?php

namespace WA\DataStore\Company;

use Alert;
use Log;
use WA\DataStore\BaseDataStore;

/**
 * Class Company.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\User\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Device\Device[] $devices
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Company\Company[] $carriers
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Udl\Udl[] $udls
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\UdlValue\UdlValue[] $udlValues
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Rule\Rule[] $rules
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Allocation\Allocation[] $allocations
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Content\Content[] $contents
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Company\CompanyCurrentBillMonth[] $currentBillMonths
 * @property-read \WA\DataStore\User\User $usersCount
 * @property-read mixed $users_count
 * @mixin \Eloquent
 */
class Company extends BaseDataStore
{
    protected $table = 'companies';

    protected $fillable = [ 
                             'name',
                             'label',
                             'active',
                             'udlpath',
                             'isCensus',
                             'udlPathRule',
                             'assetPath',
                             'shortName',
                             'currentBillMonth',
                             'defaultLocation'
                           ];

    protected $morphClass = 'company';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('WA\DataStore\User\User', 'companyId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presets()
    {
        return $this->hasMany('WA\DataStore\Preset\Preset', 'companyId');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deviceVariations()
    {
        return $this->hasMany('WA\DataStore\DeviceVariation\DeviceVariation', 'companyId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function udls()
    {
        return $this->hasMany('WA\DataStore\Udl\Udl', 'companyId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany('WA\DataStore\Rule\Rule', 'company_rules', 'companyId', 'ruleId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function addresses()
    {
        return $this->belongsToMany('WA\DataStore\Address\Address', 'company_address', 'companyId', 'addressId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations()
    {
        return $this->hasMany('WA\DataStore\Allocation\Allocation', 'companyId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packages()
    {
        return $this->hasMany('WA\DataStore\Package\Package', 'companyId');
    }

    /**
     * Get all contents that belong to a company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function contents()
    {
        $contents = $this->morphMany('WA\DataStore\Content\Content', 'owner');

        return $contents;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currentBillMonths()    {

       return $this->hasMany('WA\DataStore\Company\CompanyCurrentBillMonth', 'companyId');
    }

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new CompanyTransformer();
    }

    /**
     * Get the row of companyId.
     *
     * @return row
     */
    public function saml2Settings()
    {
        return $this->hasOne('WA\DataStore\Company\CompanySaml2', 'companyId')->first();
    }


    public function employeesCount()
    {
        $q = $this->hasOne('WA\DataStore\User\User', 'companyId')
            ->selectRaw('companyId, count(*) as total')
            ->groupBy('companyId');

        return $q;
    }

    public function getUsersCountAttribute()
    {
        if (!array_key_exists('employeesCount', $this->relations)) {
            $this->load('employeesCount');
        }

        $related = $this->getRelation('employeesCount');

        return ($related) ? (int)$related->total : 0;
    }
}
