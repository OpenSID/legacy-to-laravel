<?php

declare(strict_types=1);

namespace OpenDesa\Legacy\Helper;

use OpenDesa\Legacy\TestSupport\TestCase;

abstract class HelperTestCase extends TestCase
{
    protected function loadHelper(string $name)
    {
        require __DIR__ . '/../../../src/CI3Compatible/Helper/' . $name . '_helper.php';
    }
}
