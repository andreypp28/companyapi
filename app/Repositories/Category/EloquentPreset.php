<?php

namespace WA\Repositories\Category;

use WA\Repositories\AbstractRepository;

class EloquentPreset extends AbstractRepository implements PresetInterface
{
    /**
     * Update preset.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $preset = $this->model->find($data['id']);

        if (!$preset) {
            return 'notExist';
        }

        if (isset($data['name'])) {
            $preset->name = $data['name'];
        }

        if (!$preset->save()) {
            return 'notSaved';
        }

        return $preset;
    }

    /**
     * Get an array of all the available preset.
     *
     * @return array of preset
     */
    public function getAllPresets()
    {
        $preset = $this->model->all();

        return $preset;
    }

    /**
     * Create a new preset.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $presetData = [
            "name" => isset($data['name']) ? $data['name'] : null,
        ];

        $preset = $this->model->create($presetData);

        if (!$preset) {
            return false;
        }

        return $preset;
    }

    /**
     * Delete a preset.
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
