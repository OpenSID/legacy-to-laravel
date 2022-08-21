<?php

declare(strict_types=1);

namespace Fluent\Legacy\TestSupport;

use Fluent\Legacy\Core\CI_Controller;
use Fluent\Legacy\LogTestHelperTrait;
use Kenjis\PhpUnitHelper\ReflectionHelper;
use Kenjis\PhpUnitHelper\TestDouble;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use TestDouble;
    use ReflectionHelper;
    use LogTestHelperTrait;

    public function setUp(): void
    {
        // Initialize controller
        new CI_Controller();
    }
}
