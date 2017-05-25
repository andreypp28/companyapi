<?php

namespace WA\Repositories\UdlValue;

use WA\Repositories\RepositoryInterface;

/**
 * Interface UdlValueInterface.
 */
interface UdlValueInterface extends RepositoryInterface
{
    /**
     * Creates a new UDL value.
     *
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data);

    /**
     * Update a UDL value.
     *
     * @param $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Get the UDL value that matches the name.
     *
     * @param string $name
     * @param int    $companyId null
     *
     * @return object object of the udl information
     */
    public function byName($name, $companyId = null);

    /**
     * Get the UDL value that matches the id.
     *
     * @param int $udlValueId
     *
     * @return object object of the udl information
     */
    public function byId($udlValueId);

    /**
     * Get the UDL value that matches the name or create if it does not exist.
     *
     * @param string $name
     * @param int    $udlId
     * @param int    $companyId
     * @param int    $externalId \ 0
     *
     * @return object object of the udl information
     */
    public function byNameOrCreate($name, $udlId, $companyId, $externalId = 99999999);

    /**
     * Get all by UDL Value by the UDL ID.
     *
     * @param int $udlId
     *
     * @return array of udl values
     */
    public function byUdlId($udlId);

    /**
     * Get the User Count on the UDL Value.
     *
     * @param $name
     * @param $companyId
     *
     * @return int employee count
     */
    public function getUserCount($name, $companyId = null);
}
