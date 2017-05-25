<?php

namespace WA\Http\Requests\Parameters;

/**
 * Class Fields.
 */
class Fields
{
    /**
     * @var array
     */
    protected $fields = [];

    public function __construct($fields = [])
    {
        if (empty($fields) || !is_array($fields)) {
            return $this;
        }

        $fields = array_filter($fields);

        foreach ($fields as $type => &$members) {
            $members = \explode(',', $members);
            $members = \array_map('trim', $members);
            foreach ($members as $member) {
                $this->addField($type, $member);
            }
        }
    }

    /**
     * @param string $type
     * @param string $fieldName
     */
    public function addField($type, $fieldName)
    {
        $this->fields[(string) $type][] = (string) $fieldName;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->fields;
    }

    /**
     * @return string[]
     */
    public function types()
    {
        return array_keys($this->fields);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function members($type)
    {
        return (array_key_exists($type, $this->fields)) ? $this->fields[$type] : [];
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this->fields);
    }
}
