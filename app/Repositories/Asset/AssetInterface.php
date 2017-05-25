<?php

namespace WA\Repositories\Asset;

use WA\Repositories\CountableInterface;
use WA\Repositories\RepositoryInterface;

/**
 * Interface AssetInterface.
 */
interface AssetInterface extends RepositoryInterface, CountableInterface
{
    /**
     * Get the Asset by its identification.
     *
     * @param string $identifier
     *
     * @return \WA\DataStore\Asset\Asset
     */
    public function byIdentification($identifier);

    /**
     * Get the User attached to this asset.
     *
     * @param $user
     * @param $page
     * @param $limit
     * @param $all
     *
     * @return object object of the asset information
     */
    public function byUser($user, $page = 1, $limit = 10, $all = true);

    /**
     * Get the Device attached to this asset.
     *
     * @param $device
     * @param $page
     * @param $limit
     * @param $all
     *
     * @return object object of device information
     */
    public function byDevice($device, $page = 1, $limit = 10, $all = true);

    /**
     * Get the Unassigned assets.
     *
     * @param bool $all loads all by default, or returns a random unassigned one
     *
     * @return object object of unassigned devices
     */
    public function byUnassigned($all = true);

    /**
     * Remove assigned employee from the asset relationship.
     *
     * @param $userId
     * @param $assetId
     *
     * @return bool
     */
    public function detachByUser($userId, $assetId);

    /**
     * Get all the unique active identification on the assets.
     *
     * @param array $exclude = []
     *
     * @return mixed
     */
    public function getUniqueIdentification(array $exclude = []);

    /**
     * update an asset.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Get Asset by Search term.
     *
     * @param string $query
     * @param bool   $paginate
     * @param int    $perPage
     *
     * @return object of asset information
     */
    public function bySearch($query, $paginate = true, $perPage = 25);

    /**
     * Attach attributes to an asset.
     *
     * @param array $attributes
     * @param $model \WA\DataStore\Asset\Asset to attach attributes to
     * @param string $dataOrigination , defaults to 'wa-sys'
     * @param array  $attributes
     */
    public function attachAttributes(array $attributes, $model, $dataOrigination = 'wa-sys');

    /**
     * @param string                    $attributeName
     * @param string                    $value
     * @param \WA\DataStore\Asset\Asset $model               to attach attributes to
     * @param string                    $dataOriginationName
     *
     * @return mixed
     */
    public function updateAttribute($attributeName, $value, $model, $dataOriginationName = 'wa-sys');

    /**
     * Get the API transformer used on this data store.
     *
     * @return mixed
     */
    public function getTransformer();

    /**
     * Get the maximum value of the external ID.
     *
     * @return int
     */
    public function getMaxExternalId();
}
