<?php

namespace WA\Repositories\Package;

use WA\Repositories\RepositoryInterface;

/**
 * Interface PackageInterface.
 */
interface PackageInterface extends RepositoryInterface
{
    /**
     * Get Array of all Packages.
     *
     * @return array of Package
     */
    public function getAllPackage();

    /**
     * Create Package.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update Package.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete Package.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
