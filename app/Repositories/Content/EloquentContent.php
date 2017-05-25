<?php

namespace WA\Repositories\Content;

use WA\Repositories\AbstractRepository;

/**
 * Class EloquentContent.
 */
class EloquentContent extends AbstractRepository implements ContentInterface
{
    /**
     * Update content.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $content = $this->model->find($data['id']);

        if (!$content) {
            return false;
        }

        $content->active = isset($data['active']) ? $data['active'] : 0;
        $content->content = isset($data['content']) ? $data['content'] : null;
        $content->owner_type = isset($data['owner_type']) ? $data['owner_type'] : null;
        $content->owner_id = isset($data['owner_id']) ? $data['owner_id'] : 0;

        if (!$content->save()) {
            return false;
        }

        return $content;
    }

    /**
     * Get the company Id tied to the content.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getCompanyId($id)
    {
        return $this->model->where('id', $id)->first()->companies;
    }

    /**
     * Get an array of all the available content.
     *
     * @return array of contents
     */
    public function getAllContents()
    {
        $contents = $this->model->all();

        return $contents;
    }

    /**
     * Create new content.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $contentData = [
        'active' => !empty($data['active']) ? 1 : 0,
        'content' => isset($data['content']) ? $data['content'] : null,
        'owner_type' => isset($data['owner_type']) ? $data['owner_type'] : null,
        'owner_id' => isset($data['owner_id']) ? $data['owner_id'] : 0,
        ];

        $content = $this->model->create($contentData);

        if (!$content) {
            return false;
        }

        return $content;
    }

    /**
     * Delete Content.
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

    /**
     * Return Default Contents.
     *
     * @return mixed
     */
    public function getDefaultContent()
    {
        return $this->model->where('owner_id', 0)->get();
    }
}
