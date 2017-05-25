<?php

namespace WA\Repositories\Category;

use WA\Repositories\RepositoryInterface;

/**
 * Interface PresetInterface.
 */
interface PresetInterface extends RepositoryInterface
{
    /**
     * Get Array of all Presets.
     *
     * @return array of Preset
     */
    public function getAllPresets();

    /**
     * Create Preset.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update Preset.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete Preset.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
