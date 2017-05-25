<?php

namespace WA\Repositories\DeviceVariation;

use WA\Repositories\RepositoryInterface;

/**
 * Interface DeviceVariationInterface.
 */
interface DeviceVariationInterface extends RepositoryInterface
{
    /**
     * Get Array of all DeviceVariations.
     *
     * @return array of DeviceVariation
     */
    public function getAllDeviceVariation();

    /**
     * Create DeviceVariation.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update DeviceVariation.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete DeviceVariation.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);

    /**
     * Get Array of all DeviceVariations Devices.
     *
     * @return array of DeviceVariations
     */
    public function getDeviceVariationDevices($id);

    /**
     * Get Array of all DeviceVariations Carriers.
     *
     * @return array of DeviceVariations
     */
    public function getDeviceVariationCarriers($id);

    /**
     * Get Array of all DeviceVariations Companies.
     *
     * @return array of DeviceVariations
     */
    public function getDeviceVariationCompanies($id);
}
