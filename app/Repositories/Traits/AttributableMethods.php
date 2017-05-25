<?php

namespace WA\Repositories\Traits;

use App;
use WA\Repositories\Attribute\AttributeInterface;
use WA\Repositories\DataOrigination\DataOriginationInterface;

/**
 * Common methods for attaching/adding/updating attributes to an attributable model.
 */
trait AttributableMethods
{
    /**
     * @var array
     */
    protected $dataOriginations = null;

    /**
     * @var array
     */
    protected $attributes = null;

    /**
     * @var DataOriginationInterface
     */
    protected $dataOriginationsRepo = null;

    /**
     * @var AttributeInterface
     */
    protected $attributesRepo = null;

    protected function setAccessors()
    {
        if (!isset($this->dataOriginations)) {
            $this->dataOriginationsRepo = App::make('WA\Repositories\DataOrigination\DataOriginationInterface');
            $this->dataOriginations = $this->dataOriginationsRepo->getArray();
        }

        if (!isset($this->attributes)) {
            $this->attributesRepo = App::make('WA\Repositories\Attribute\AttributeInterface');
            $this->attributes = $this->attributesRepo->getArray();
        }
    }

    /**
     * Attach attributes to a model.
     *
     * @param array     $attributes
     * @param \StdClass $model           to attach attributes to
     * @param string    $dataOrigination , defaults to 'wa-sys'
     * @param array     $attributes
     */
    public function attachAttributes(array $attributes, $model, $dataOrigination = 'wa-sys')
    {
        foreach ($attributes as $attribute => $value) {
            $this->updateAttribute($attribute, $value, $model, $dataOrigination);
        }
    }

    /**
     * Update a single attribute on this model.
     *
     * @param string    $attributeName
     * @param string    $value
     * @param \StdClass $model
     * @oaram string $dataOriginationName
     *
     * @return bool
     */
    public function updateAttribute($attributeName, $value, $model, $dataOriginationName = 'wa-sys')
    {
        $this->setAccessors();

        $dataOriginationNameId = $this->dataOriginations[$dataOriginationName];
        $attribute = $this->attributes[$attributeName];

        $attr = $model->attributes()
            ->where('attribute_id', $attribute)
            ->wherePivot('dataOriginationId', $dataOriginationNameId)
            ->first();

        if ($attr === null) {
            $this->createAttribute($attributeName, $value, $model, $dataOriginationName);

            return true;
        }

        if ($value != $attr->pivot->value) {
            \Log::debug("Updating attribute $attributeName / $value for $model->identification");
            $model->attributes()->updateExistingPivot($attribute, ['value' => $value]);

            return true;
        }

        return true;
    }

    /**
     * Create attributes on this model.
     *
     * @param string    $attributeName
     * @param string    $value
     * @param \StdClass $model
     * @oaram string $dataOriginationName
     *
     * @return bool
     */
    public function createAttribute($attributeName, $value, $model, $dataOriginationName = 'wa-sys')
    {
        $this->setAccessors();

        $attribute = $this->attributesRepo->byName($attributeName);
        $dataOriginationNameId = $this->dataOriginations[$dataOriginationName];

        $model->attributes()->save($attribute, ['value' => $value, 'dataOriginationId' => $dataOriginationNameId]);

        return true;
    }

    /**
     * @param $attributeName
     * @param $model
     *
     * @return mixed
     */
    public function getAttribute($attributeName, $model)
    {
        $this->setAccessors();

        $attribute = $this->attributesRepo->byName($attributeName);

        return $model->attributes()->where('attribute_id', $attribute->id)->first();
    }
}
