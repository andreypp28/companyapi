<?php

namespace WA\Testing\Http\Controllers;

use TestCase;

class FilteredApiControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCanConstructAnExtendedController()
    {
        $controller = app()->make('WA\Http\Controllers\DevicesController');
        $this->assertInstanceOf(\WA\Http\Controllers\FilteredApiController::class, $controller);
    }

}
