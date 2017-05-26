<?php

namespace WA\Testing\Http\Requests\Parameters;

use TestCase;
use WA\Http\Requests\Parameters\Filters;

class FilterParameterTest extends TestCase
{
    /**
     * @var Filters
     */
    private $filters;

    public function setUp()
    {
        $this->filters = new Filters();
    }

    public function testInputViaConstructor()
    {
        $filterArray = ['name' => '123'];
        $filters = new Filters($filterArray);
        $this->assertEquals(['name' => ['eq' => '123']], $filters->filtering());
    }

    public function testInputViaAddFilterMethod()
    {
        $filters = new Filters();
        $filters->addFilter('name', 'eq', 'bob');
        $this->assertEquals(['name' => ['eq' => 'bob']], $filters->filtering());
    }

    public function testWithMultipleFields()
    {
        $filterArray = ['name' => 'bob', 'label' => 'notbob'];
        $filters = new Filters($filterArray);

        $this->assertEquals([
            'name'  => ['eq' => 'bob'],
            'label' => ['eq' => 'notbob']
        ], $filters->filtering());
        $this->assertEquals(['name', 'label'], $filters->fields());
    }

    public function testWithMultipleCriteriaPerField()
    {
        $filterArray = ['cost' => ['lt' => '83', 'gt' => '50']];
        $filters = new Filters($filterArray);
        $this->assertEquals(['cost' => ['lt' => '83', 'gt' => '50']], $filters->filtering());
    }

    public function testWithMultipleCriteriaMultipleFields()
    {
        $filterArray = ['cost' => ['lt' => '83', 'gt' => '50'], 'name' => '123'];
        $filters = new Filters($filterArray);
        $this->assertEquals(['cost' => ['lt' => '83', 'gt' => '50'], 'name' => ['eq' => '123']], $filters->filtering());
    }

    public function testIsEmpty()
    {
        $filters = new Filters();
        $this->assertEquals(true, $filters->isEmpty());

        $filters->addFilter('name', 'eq', 'bob');

        $this->assertEquals(false, $filters->isEmpty());
    }


}
