<?php

declare(strict_types=1);

namespace Fluent\Legacy\Helper;

use Fluent\Legacy\TestSupport\TestCase;

abstract class HelperTestCase extends TestCase
{
    protected function loadHelper(string $name)
    {
        require __DIR__ . '/../../../src/CI3Compatible/Helper/' . $name . '_helper.php';
    }
}
