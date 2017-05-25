<?php

namespace WA\DataStore\UdlValuePathUsers;

use WA\DataStore\FilterableTransformer;

/**
 * Class UdlValuePathCreatorUserTransformer.
 */
class UdlValuePathUsersTransformer extends FilterableTransformer
{
    /**
     * @param UdlValuePathUsers $udlValuePathUsers
     *
     * @return array
     */
    public function transform(UdlValuePathUsers $udlValuePathUsers)
    {
        return [
            'id'             => $udlValuePathUsers->id,
            'creatorId'      => $udlValuePathUsers->creatorId,
            'userEmail'      => $udlValuePathUsers->userEmail,
            'userFirstName'  => $udlValuePathUsers->userFirstName,
            'userLastName'   => $udlValuePathUsers->userLastName,
            'udlValuePathId' => $udlValuePathUsers->udlValuePathId,

        ];
    }
}
