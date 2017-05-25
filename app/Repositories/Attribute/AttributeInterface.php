<?php

namespace WA\Repositories\Attribute;

interface AttributeInterface
{
    /**
     * Get the attribute information by it's name.
     *
     * @param $name
     *
     * @return object object
     */
    public function byName($name);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getArray($key = 'name');
}
