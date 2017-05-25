<?php

namespace WA\Repositories\DeviceType;

use WA\Repositories\AbstractRepository;

class EloquentDeviceType extends AbstractRepository implements DeviceTypeInterface
{
    /**
     * Get the Device Type by is model name.
     *
     * @param $model
     *
     * @return Object object of device type
     */
    public function byModel($model)
    {
        return $this->model->where('model', $model)->first();
    }

    /**
     * Get the Device Type by is model name or create if it doesn't exist.
     *
     * @param string $model
     * @param array  $data
     *
     * @return Object object of device type
     */
    public function byModelOrCreate($model, array $data)
    {
        $type = $this->byModel($model);

        if (!$type) {
            $type = $this->create($data);
        }

        return $type;
    }

    /**
     * Update DeviceType.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $deviceType = $this->model->find($data['id']);

        if (!$deviceType) {
            return 'notExist';
        }

        if (isset($data['make'])) {
            $deviceType->make = $data['make'];
        }
        if (isset($data['model'])) {
            $deviceType->model = $data['model'];
        }
        if (isset($data['class'])) {
            $deviceType->class = $data['class'];
        }
        if (isset($data['deviceOS'])) {
            $deviceType->deviceOS = $data['deviceOS'];
        }
        if (isset($data['description'])) {
            $deviceType->description = $data['description'];
        }
        if (isset($data['statusId'])) {
            $deviceType->statusId = $data['statusId'];
        }
        if (isset($data['image'])) {
            $deviceType->image = $data['image'];
        }

        if (!$deviceType->save()) {
            return 'notSaved';
        }

        return $deviceType;
    }

    /**
     * Get an array of all the available DeviceType.
     *
     * @return array of DeviceType
     */
    public function getAllDeviceType()
    {
        $deviceType = $this->model->all();

        return $deviceType;
    }

    /**
     * Create a new DeviceType.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $deviceTypeData = [
            'make' => isset($data['make']) ? $data['make'] : null,
            'model' => isset($data['model']) ? $data['model'] : null,
            'class' => isset($data['class']) ? $data['class'] : null,
            'deviceOS' => isset($data['deviceOS']) ? $data['deviceOS'] : null,
            'description' => isset($data['description']) ? $data['description'] : null,
            'statusId' => isset($data['statusId']) ? $data['statusId'] : null,
            'image' => isset($data['image']) ? $data['image'] : null,
        ];

        //var_dump($deviceTypeData);
        //die;

        $deviceType = $this->model->create($deviceTypeData);

        //var_dump($deviceType);
        //die;

        if (!$deviceType) {
            return false;
        }

     

        return $deviceType;
    }

    /**
     * Delete a DeviceType.
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
