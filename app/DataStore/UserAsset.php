<?php

namespace WA\DataStore;

use WA\DataStore\Traits\BelongsToAsset;
use WA\DataStore\Traits\BelongsToDevice;
use WA\DataStore\Traits\BelongsToUser;

/**
 * An Eloquent Model: 'WA\DataStore\UserAsset'.
 *
 * @property int $id
 * @property int $userId
 * @property int $assetId
 * @property int $deviceId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \WA\DataStore\Device\Device $device
 * @property-read \WA\DataStore\Asset\Asset $asset
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserAsset whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserAsset whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserAsset whereAssetId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UserAsset whereUpdatedAt($value)
 */
class UserAsset extends BaseDataStore
{
    protected $tableName;

    use BelongsToDevice,
        BelongsToAsset,
        BelongsToUser;
}
