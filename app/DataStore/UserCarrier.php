<?php

namespace WA\DataStore;

use WA\DataStore\Traits\BelongsToCarrier;
use WA\DataStore\Traits\BelongsToUser;

/**
 * An Eloquent Model: 'WA\DataStore\UserCarrier'.
 *
 * @property int $id
 * @property int $carrierId
 * @property int $userId
 * @property string $userName
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \WA\DataStore\Carrier\Carrier $carrier
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserCarrier whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserCarrier whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserCarrier whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserCarrier whereUserName($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserCarrier whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserCarrier whereUpdatedAt($value)
 */
class UserCarrier extends BaseDataStore
{
    protected $tableName = 'user_carrier';

    protected $fillable = [
        'carrierId',
        'userId',
        'userName',
    ];

    use BelongsToUser,
        BelongsToCarrier;
}
