<?php

declare(strict_types=1);

namespace OpenDesa\Legacy\TestSupport;

use OpenDesa\Legacy\Core\CI_Controller;
use OpenDesa\Legacy\LogTestHelperTrait;
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
