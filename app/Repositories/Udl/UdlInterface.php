<?php

namespace WA\Repositories\Udl;

use WA\Repositories\RepositoryInterface;

/**
 * Interface UdlInterface.
 */
interface UdlInterface extends RepositoryInterface
{
    /**
     * UDL by the id.
     *
     * @param int $id
     *
     * @return object object of the UDL information
     */
    public function byId($id);

    /**
     * Get the UDL information by the name.
     *
     * @param string $name
     *
     * @return object of the UDL information
     */
    public function byLabel($name);

    /**
     * Get the UDL information by the name.
     *
     * @param string $name
     * @param int    $companyId | null strongly suggested to include this as many company have the same UDL names
     *
     * @return object of the UDL information
     */
    public function byName($name, $companyId = null);

    /**
     * Get the UDL values of a UDL.
     *
     * @param int $id
     *
     * @return object object information of the UDL Values
     */
    public function byUDLValue($id);

    /**
     * Update a UDL.
     *
     * @param $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Create UDL.
     *
     * @param $data
     *
     * @return bool
     */
    public function create(array $data);

    /**
     * Get UDL by Company ID.
     *
     * @param int $companyId
     *
     * @return array of UDL and Values
     */
    public function byCompanyId($companyId);
}
