<?php

namespace WA\Testing\Http\Requests\Parameters;

use TestCase;
use WA\Http\Requests\Parameters\Fields;

class FieldParameterTest extends TestCase
{
    /**
     * @var Fields
     */
    private $fields;

    public function setUp()
    {
        $this->fields = new Fields();
        $this->fields->addField('company', 'name');
        $this->fields->addField('company', 'label');
    }

    public function testItWillReturnAllFieldData()
    {
        $expected = [
            'company' => [
                'name',
                'label',
            ],
        ];
        $this->assertEquals($expected, $this->fields->get());
    }

    public function testItWillReturnTypeMembers()
    {
        $expected = ['name', 'label'];
        $this->assertEquals($expected, $this->fields->members('company'));
    }

    public function testItWillReturnMemberNames()
    {
        $expected = ['company'];
        $this->assertEquals($expected, $this->fields->types());
    }

    public function testIsEmpty()
    {
        $this->assertFalse($this->fields->isEmpty());
    }
}
