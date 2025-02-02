<?php

declare(strict_types=1);

namespace OpenDesa\Legacy\Test\Traits;

use App\Controllers\MY_Controller;
use OpenDesa\Legacy\Core\CI_Controller;
use OpenDesa\Legacy\TestSupport\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ResetInstanceTest extends TestCase
{
    /** @var MockObject */
    private $resetInstance;

    public function setUp(): void
    {
        parent::setUp();

        $this->resetInstance = $this->getMockForTrait(ResetInstance::class);
    }

    public function test_createCodeIgniterInstance(): void
    {
        $controller = $this->resetInstance->createCodeIgniterInstance();

        $this->assertInstanceOf(CI_Controller::class, $controller);
    }

    public function test_createCodeIgniterInstance_MY_Controller(): void
    {
        $controller = $this->resetInstance->createCodeIgniterInstance(true);

        $this->assertInstanceOf(MY_Controller::class, $controller);
    }
}
