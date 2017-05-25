<?php

namespace WA\DataStore\Udl;

use WA\DataStore\BaseDataStore;

/**
 * WA\DataStore\Udl\Udl.
 *
 * @property int $id
 * @property int $companyId
 * @property string $name
 * @property string $label
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\UdlValue\UdlValue[] $udlValues
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Udl\Udl whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Udl\Udl whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Udl\Udl whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Udl\Udl whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Udl\Udl whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Udl\Udl whereUpdatedAt($value)
 */
class Udl extends BaseDataStore
{
    protected $fillable = ['companyId', 'name', 'inputType'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function udlValues()
    {
        return $this->hasMany('WA\DataStore\UdlValue\UdlValue', 'udlId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('WA\DataStore\Company\Company', 'companyId');
    }

    /**
     * @return mixed|UdlTransformer
     */
    public function getTransformer()
    {
        return new UdlTransformer();
    }
}
