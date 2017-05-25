<?php

namespace WA\DataStore;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\MessageBag;
use WA\DataStore\Overrides\MorphToMany as WAMorphToMany;
use WA\Events\BaseDataStoreObserver;

/**
 * An Eloquent Model: 'WA\DataStore\BaseDataStore'.
 *
 * @mixin \Eloquent
 */
class BaseDataStore extends BaseModel
{
    /**
     * The message bag instance containing validation error messages.
     *
     * @var \Illuminate\Support\MessageBag
     */
    public $validationErrors;

    /**
     * Create a new Eloquent model instance using option attributes.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->validationErrors = new MessageBag();
    }

    public static function boot()
    {
        parent::boot();
        parent::observe(new BaseDataStoreObserver());
    }

    /**
     * Same as Base Laravel, expect, return our own override
     * see WA\DataStore\Overrides\MorphToMany:L54
     * ** fixes issues with our L4.1(bug?) stores MorphToMany with namespaces
     * ** filed bug report: https://github.com/laravel/framework/issues/4006.
     *
     * @param string $related
     * @param string $name
     * @param null   $table
     * @param null   $foreignKey
     * @param null   $otherKey
     * @param bool   $inverse
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|WAMorphToMany
     */
    public function morphToMany($related, $name, $table = null, $foreignKey = null, $otherKey = null, $inverse = false)
    {
        $caller = $this->getBelongsToManyCaller();
        $foreignKey = $foreignKey ?: $name.'_id';
        $instance = new $related();
        $otherKey = $otherKey ?: $instance->getForeignKey();
        $query = $instance->newQuery();
        $table = $table ?: str_plural($name);

        return new WAMorphToMany($query, $this, $name, $table, $foreignKey, $otherKey, $caller, $inverse);
    }

    /**
     * Generate a key cache.
     *
     * @param int $id
     *
     * @return string
     */
    public static function generateKeyCache($id)
    {
        return 'eloquent_'.get_called_class().'_'.$id;
    }

    /**
     * @return MessageBag
     */
    public function errors()
    {
        return $this->validationErrors;
    }

    /**
     * @return bool
     */
    public function getNext()
    {
        if (isset($this->id)) {
            return $this->where('id', '>', $this->id)->min('id');
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getPrevious()
    {
        if (isset($this->id)) {
            return $this->where('id', '<', $this->id)->max('id');
        }

        return false;
    }

    /**
     * @return array
     */
    public function getTableColumns()
    {
        return \Cache::remember($this->getTable() . '-columns', 5, function () {
            return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        });
    }
}
