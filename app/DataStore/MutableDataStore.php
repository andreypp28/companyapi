<?php

namespace WA\DataStore;

use Illuminate\Config\Repository;
use WA\DataStore\Company;

/**
 * WA\DataStore\MutableDataStore
 *
 * @mixin \Eloquent
 */
class MutableDataStore extends BaseDataStore
{
    protected $cloneTablePrefix = 'clone_';

    public function __construct(array $attributes = [])
    {
        $this->setTable($this->table);
        parent::__construct($attributes);
    }

    /**
     * Set the table associated with the model.
     *
     * @param  string $table
     * @param  Repository $config | null
     *
     * @return void
     */
    public function setTable($table, Repository $config = null)
    {
        $config = $config ?: app()->make('Illuminate\Config\Repository');

        $data_loader_mode = $config->get('settings.cdi.mode');

        $this->table = $table;

        if ($data_loader_mode === 1) {
            $this->table = $this->cloneTablePrefix . $this->table;
        }
    }
}
