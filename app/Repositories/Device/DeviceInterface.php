<?php

namespace WA\Repositories\Device;

use WA\Repositories\RepositoryInterface;

interface DeviceInterface extends RepositoryInterface
{
    /**
     * Get the device information by their employee.
     *
     * @param $userId
     *
     * @return \WA\DataStore\Device\Device object of device information by the employee
     */
    public function byUser($userId);

    /**
     * Get device by their identification.
     *
     * @param $identification
     *
     * @return \WA\DataStore\Device\Device object of device information
     */
    public function byIdentification($identification);

    /**
     * Get all the unique identifications.
     *
     * @param array $exclude
     *
     * @return array of identification
     */
    public function getUniqueIdentification(array $exclude = []);

    /**
     * Sync an asset to devices.
     *
     * @param int   $id  of the device
     * @param array $ids of the assets to sync device with
     *
     * @return bool
     */
    public function syncAsset($id, array $ids);

    /**
     * Get the devices that are pending review ( that have not yet being assigned users).
     *
     * @param bool $all
     *
     * @return \WA\DataStore\Device\Device object of device information, for unassigned
     */
    public function byUnassigned($all = true);

    /**
     * Attach attributes to a device.
     *
     * @param array                       $attributes
     * @param \WA\DataStore\Device\Device $model           to attach attributes to
     * @param string                      $dataOrigination DataOrigination defaults to 'wa-sys'
     */
    public function attachAttributes(array $attributes, $model, $dataOrigination = 'wa-sys');

    /**
     * Get companies devices.
     *
     * @param $id
     *
     * @return object object of company
     */
    public function byCompany($id);

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

    /**
     * Update a repository.
     *
     * @param array $data to be updated
     *
     * @return object object of updated repo
     */
    public function update(array $data);
    public function create(array $data);
}
