<?php

namespace WA\Repositories\UdlValuePathUsers;

use WA\Repositories\RepositoryInterface;

interface UdlValuePathUsersInterface extends RepositoryInterface
{
    /**
     * Get the ExternalId value that matches the UDLValuePathId.
     *
     * @param string $udlValuePathId
     *
     * @return object object of the udlValuePathUsers information
     */
    public function byUdlPathId($udlValuePathId);

    /**
     * Get the ExternalId value that matches the creator Id.
     *
     * @param int $creatorId
     *
     * @return object object of the udlValuePathUsers information
     */
    public function byCreatorId($creatorId);
}
