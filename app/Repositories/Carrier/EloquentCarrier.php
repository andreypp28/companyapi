<?php

namespace WA\Repositories\Carrier;

use WA\Repositories\AbstractRepository;

class EloquentCarrier extends AbstractRepository implements CarrierInterface
{
    /**
     * Get the ID of a carrier by its name.
     *
     * @param $name
     *
     * @return int id of of the carrier
     */
    public function getIdByName($name)
    {
        return $this->model->where('name', $name)->pluck('id');
    }

    /**
     * Get companies devices.
     *
     * @param $id
     *
     * @return Object object of company
     */
    public function byCompany($id)
    {
        $response = $this->model->whereHas('companies', function ($q) use ($id) {
            $q->where('companyId', $id);
        });

        return $response->get();
    }

    /**
     * Get carrier short name by Id.
     *
     * @param $id
     *
     * @return string short name of the carrier
     */
    public function getShortNameById($id)
    {
        return $this->model->where('id', $id)->pluck('shortName');
    }

    /**
     * Get the ID of a carrier by its presentation.
     *
     * @param $presentation
     *
     * @return int id of of the carrier
     */
    public function getIdByPresentation($presentation)
    {
        return $this->model->where('presentation', $presentation)->pluck('id');
    }

    /**
     * Update Service.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $carrier = $this->model->find($data['id']);

        if (!$carrier) {
            return 'notExist';
        }

        if (isset($data['name'])) {
            $carrier->name = $data['name'];
        }
        if (isset($data['presentation'])) {
            $carrier->presentation = $data['presentation'];
        }
        if (isset($data['active'])) {
            $carrier->active = $data['active'];
        }
        if (isset($data['locationId'])) {
            $carrier->locationId = $data['locationId'];
        }
        if (isset($data['shortName'])) {
            $carrier->shortName = $data['shortName'];
        }
        
        if (!$carrier->save()) {
            return 'notSaved';
        }

        return $carrier;
    }
}
