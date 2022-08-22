<?php

declare(strict_types=1);

namespace Fluent\Legacy\Test\TestCase;

use App\Controllers\MY_Controller;
use Fluent\Legacy\Core\CI_Controller;
use Fluent\Legacy\Test\TestCase\TestCase as CI3TestCase;
use Fluent\Legacy\TestSupport\TestCase;

class TestCaseTest extends TestCase
{
    public function test_resetInstance()
    {
        $testCase = new CI3TestCase();

        $oldController = get_instance();
        $testCase->resetInstance();
        $newController = get_instance();

        $this->assertNotSame($oldController, $newController);
        $this->assertInstanceOf(CI_Controller::class, $newController);
    }

    public function test_resetInstance_MY_Controller()
    {
        $testCase = new CI3TestCase();

        $oldController = get_instance();
        $testCase->resetInstance(true);
        $newController = get_instance();

        $this->assertNotSame($oldController, $newController);
        $this->assertInstanceOf(MY_Controller::class, $newController);
    }
}
