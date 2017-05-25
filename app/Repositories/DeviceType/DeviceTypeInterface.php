<?php

namespace WA\Repositories\DeviceType;

use WA\Repositories\RepositoryInterface;

interface DeviceTypeInterface extends RepositoryInterface
{
    /**
     * Get the Device Type by is model name.
     *
     * @param string $name
     *
     * @return object object of device type
     */
    public function byModel($name);

    /**
     * Get the Device Type by is model name or create if it doesn't exist.
     *
     * @param string $name
     * @param array  $data
     *
     * @return object object of device type
     */
    public function byModelOrCreate($name, array $data);

    /**
     * Get Array of all DeviceTypes.
     *
     * @return array of DeviceType
     */
    public function getAllDeviceType();

    /**
     * Create DeviceType.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update DeviceType.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete DeviceType.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
