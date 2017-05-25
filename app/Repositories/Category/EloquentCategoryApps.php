<?php

namespace WA\Repositories\Category;

use WA\Repositories\AbstractRepository;

class EloquentCategoryApps extends AbstractRepository implements CategoryAppsInterface
{
    /**
     * Update category.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $category = $this->model->find($data['id']);

        if (!$category) {
            return 'notExist';
        }

        if (isset($data['name'])) {
            $category->name = $data['name'];
        }

        if (!$category->save()) {
            return 'notSaved';
        }

        return $category;
    }

    /**
     * Get an array of all the available category.
     *
     * @return Array of category
     */
    public function getAllCategories()
    {
        $category = $this->model->all();
        return $category;
    }

    /**
     * Create a new category.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $categoryData = [
            "name" =>  isset($data['name']) ? $data['name'] : null,
        ];

        $category = $this->model->create($categoryData);

        if (!$category) {
            return false;
        }

        return $category;
    }

    /**
     * Delete a category.
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
