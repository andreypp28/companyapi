<?php

namespace WA\Repositories\UdlValuePath;

use WA\Repositories\RepositoryInterface;

interface UdlValuePathInterface extends RepositoryInterface
{
    /**
     * Get the ExternalId value that matches the UDLPath.
     *
     * @param string $udlValuePath
     *
     * @return object object of the udl information
     */
    public function byUdlPath($udlValuePath);

    /**
     * Get the ExternalId value that matches the UDLPath Id.
     *
     * @param int $udlValuePathId
     *
     * @return int ExternalId
     */
    public function byUdlId($udlValuePathId);

    /**
     * Get the Path Name by the External ID.
     *
     * @param int $externalId
     *
     * @return string Path Name
     */
    public function byExternalId($externalId);

    /**
     * Get the maximum value of the external ID.
     *
     * @return int
     */
    public function getMaxExternalId();
}
