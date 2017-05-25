<?php

namespace WA\DataStore\Overrides;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * Class MorphToMany.
 */
class MorphToMany extends BelongsToMany
{
    /**
     * The type of the polymorphic relation.
     *
     * @var string
     */
    protected $morphType;

    /**
     * The class name of the morph type constraint.
     *
     * @var string
     */
    protected $morphClass;

    /**
     * Indicates if we are connecting the inverse of the relation.
     *
     * This primarily affects the morphClass constraint.
     *
     * @var bool
     */
    protected $inverse;

    /**
     * Create a new has many relationship instance.
     *
     * @param Builder     $query
     * @param Model       $parent
     * @param string      $name
     * @param string      $table
     * @param string      $foreignKey
     * @param null|string $otherKey
     * @param null        $relationName
     * @param bool        $inverse
     */
    public function __construct(
        Builder $query,
        Model $parent,
        $name,
        $table,
        $foreignKey,
        $otherKey,
        $relationName = null,
        $inverse = false
    ) {
        $this->inverse = $inverse;
        $this->morphType = $name.'_type';
        $this->morphClass = $inverse ? get_class($query->getModel()) : get_class($parent);

        // the change we care about see WA\DataStore\BaseDataStore:L151:
        $this->morphClass = substr($this->morphClass, strrpos($this->morphClass, '\\') + 1);

        parent::__construct($query, $parent, $table, $foreignKey, $otherKey, $relationName);
    }

    /**
     * Get the foreign key "type" name.
     *
     * @return string
     */
    public function getMorphType()
    {
        return $this->morphType;
    }

    /**
     * Get the class name of the parent model.
     *
     * @return string
     */
    public function getMorphClass()
    {
        return $this->morphClass;
    }

    /**
     * Set the where clause for the relation query.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected function setWhere()
    {
        parent::setWhere();

        $this->query->where($this->table.'.'.$this->morphType, $this->morphClass);

        return $this;
    }

    /**
     * Add the constraints for a relationship count query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Builder $parent
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRelationCountQuery(Builder $query, Builder $parent)
    {
        $query = parent::getRelationCountQuery($query, $parent);

        return $query->where($this->table.'.'.$this->morphType, $this->morphClass);
    }

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param array $models
     */
    public function addEagerConstraints(array $models)
    {
        parent::addEagerConstraints($models);

        $this->query->where($this->table.'.'.$this->morphType, $this->morphClass);
    }

    /**
     * Create a new pivot attachment record.
     *
     * @param int  $id
     * @param bool $timed
     *
     * @return array
     */
    protected function createAttachRecord($id, $timed)
    {
        $record = parent::createAttachRecord($id, $timed);

        return array_add($record, $this->morphType, $this->morphClass);
    }

    /**
     * Create a new query builder for the pivot table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newPivotQuery()
    {
        $query = parent::newPivotQuery();

        return $query->where($this->morphType, $this->morphClass);
    }

    /**
     * Create a new pivot model instance.
     *
     * @param array $attributes
     * @param bool  $exists
     *
     * @return \Illuminate\Database\Eloquent\Relations\Pivot
     */
    public function newPivot(array $attributes = [], $exists = false)
    {
        $pivot = new MorphPivot($this->parent, $attributes, $this->table, $exists);

        $pivot->setPivotKeys($this->foreignKey, $this->otherKey);

        $pivot->setMorphType($this->morphType);

        return $pivot;
    }
}
