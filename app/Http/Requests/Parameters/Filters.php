<?php

namespace WA\Http\Requests\Parameters;

/**
 * Class Filters.
 */
class Filters
{
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * Filters constructor.
     *
     * @param array $filters
     */
    public function __construct($filters = [])
    {
        if (empty($filters)) {
            return $this;
        }

        foreach ($filters as $field => $criteria) {
            if (is_array($criteria)) {
                foreach ($criteria as $op => $val) {
                    $this->addFilter($field, $op, $val);
                }
            } else {
                if (is_string($criteria)) {
                    $op = 'eq';
                    $val = $criteria;
                } else {
                    $op = key($criteria);
                    $val = current($criteria);
                }
                $this->addFilter($field, $op, $val);
            }
        }
    }

    /**
     * Return a string representation of the filters, suitable for meta data.
     *
     * @return string
     */
    public function get()
    {
        $get = [];

        // @TODO: This could be made recursive to save 6 lines of code.
        foreach ($this->filters as $field => $criteria) {
            if (is_array($criteria)) {
                foreach ($criteria as $op => $val) {
                    if ($op == 'eq') {
                        $get[] = "[$field]=$val";
                    } else {
                        $get[] = "[$field][$op]=$val";
                    }
                }
            } else {
                $op = key($criteria);
                $val = current($criteria);
                if ($op == 'eq') {
                    $get[] = "[$field]=$val";
                } else {
                    $get[] = "[$field][$op]=$val";
                }
            }
        }

        return $get;
    }

    /**
     * @return array
     */
    public function filtering()
    {
        return $this->filters;
    }

    /**
     * @return array
     */
    public function fields()
    {
        return array_keys($this->filters);
    }

    /**
     * @param $field
     * @param $op
     * @param $value
     */
    public function addFilter($field, $op, $value)
    {
        if (!isset($this->filters[$field]) || !is_array($this->filters[$field])) {
            $this->filters[$field] = [];
        }
        $this->filters[$field][$op] = $value;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this->filters);
    }
}
