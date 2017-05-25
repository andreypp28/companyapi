<?php

namespace WA\Repositories\App;

use WA\Repositories\AbstractRepository;

/**
 * Class EloquentApp.
 */
class EloquentApp extends AbstractRepository implements AppInterface
{
    /**
     * Update App.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $app = $this->model->find($data['id']);

        if (!$app) {
            return 'notExist';
        }

        if (isset($data['type'])) {
            $app->type = $data['type'];
        }
        if (isset($data['image'])) {
            $app->image = $data['image'];
        }
        if (isset($data['description'])) {
            $app->description = $data['description'];
        }

        if (!$app->save()) {
            return 'notSaved';
        }

        return $app;
    }

    /**
     * Get an array of all the available App.
     *
     * @return array of App
     */
    public function getAllApp()
    {
        $app = $this->model->all();

        return $app;
    }

    /**
     * Create a new app.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $appData = [
            "type" =>  isset($data['type']) ? $data['type'] : null ,
            "image" => isset($data['image']) ? $data['image'] : null,
            "description" => isset($data['description']) ? $data['description'] : null,
        ];

        $app = $this->model->create($appData);

        if (!$app) {
            return false;
        }

        return $app;
    }

    /**
     * Delete a App.
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
