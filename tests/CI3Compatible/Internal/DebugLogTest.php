<?php

declare(strict_types=1);

namespace OpenDesa\Legacy\Internal;

use OpenDesa\Legacy\TestSupport\TestCase;

class DebugLogTest extends TestCase
{
    public function test_log_outputs_short_classname()
    {
        $classAndMethod = 'OpenDesa\Legacy\Core\Loader\LibraryLoader::injectTo';
        $message = 'CodeIgniter\View\View::$session already exists';
        DebugLog::log($classAndMethod, $message);

        $log = '[LibraryLoader::injectTo] CodeIgniter\View\View::$session already exists';
        $this->assertLogged('debug', $log);
    }
}
