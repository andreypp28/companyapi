<?php

namespace WA\Repositories\ServiceItem;

use WA\Repositories\AbstractRepository;

/**
 * Class EloquentServiceItems.
 */
class EloquentServiceItem extends AbstractRepository implements ServiceItemInterface
{
    /**
     * Update ServiceItems.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $item = $this->model->find($data['id']);

        if (!$item) {
            return 'notExist';
        }

        if (isset($data['serviceId'])) {
            $item->serviceId = $data['serviceId'];
        }
        if (isset($data['category'])) {
            $item->category = $data['category'];
        }
        if (isset($data['description'])) {
            $item->description = $data['description'];
        }
        if (isset($data['value'])) {
            $item->value = $data['value'];
        }
        if (isset($data['unit'])) {
            $item->unit = $data['unit'];
        }
        if (isset($data['cost'])) {
            $item->cost = $data['cost'];
        }
        if (isset($data['domain'])) {
            $item->domain = $data['domain'];
        }

        if (!$item->save()) {
            return 'notSaved';
        }

        return $item;
    }

    /**
     * Get an array of all the available serviceItems.
     *
     * @return array of serviceItems
     */
    public function getAllServiceItems()
    {
        $items = $this->model->all();

        return $items;
    }

    /**
     * Create a new serviceItem.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $itemData = [
            "serviceId" =>  isset($data['serviceId']) ? $data['serviceId'] : null ,
            "category" => isset($data['category']) ? $data['category'] : null,
            "description" => isset($data['description']) ? $data['description'] : '',
            "value" =>  isset($data['value']) ? $data['value'] : null ,
            "unit" => isset($data['unit']) ? $data['unit'] : null,
            "cost" => isset($data['cost']) ? $data['cost'] : null,
            "domain" => isset($data['domain']) ? $data['domain'] : null,
        ];

        $item = $this->model->create($itemData);

        if (!$item) {
            return false;
        }

        return $item;
    }

    /**
     * Delete a serviceItem.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true)
    {
        if (!$this->model->find($id)) {
            return false;
        }

        if (!$soft) {
            $this->model->forceDelete($id);
        }

        return $this->model->destroy($id);
    }
}
