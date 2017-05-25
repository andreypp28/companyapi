<?php

namespace WA\Repositories\Carrier;

use WA\Repositories\RepositoryInterface;

interface CarrierInterface extends RepositoryInterface
{
    /**
     * Get the ID of a carrier by itsElo name.
     *
     * @param $name
     *
     * @return int id of of the carrier
     */
    public function getIdByName($name);

    /**
     * Get companies devices.
     *
     * @param $id
     *
     * @return object object of company
     */
    public function byCompany($id);

    /**
     * Get carrier short name by Id.
     *
     * @param $id
     *
     * @return string short name of the carrier
     */
    public function getShortNameById($id);

    /**
     * Get the ID of a carrier by its presentation.
     *
     * @param $presentation
     *
     * @return int id of of the carrier
     */
    public function getIdByPresentation($presentation);
}
