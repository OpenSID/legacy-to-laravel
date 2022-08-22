<?php

declare(strict_types=1);

/*
 * Copyright (c) 2021 Kenji Suzuki
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/kenjis/ci3-to-4-upgrade-helper
 */

namespace Fluent\Legacy\Core\Loader;

use Fluent\Legacy\Core\CI_Controller;
use Fluent\Legacy\Internal\DebugLog;

class ControllerPropertyInjector
{
    /** @var CI_Controller */
    private $controller;

    public function __construct(CI_Controller $controller)
    {
        $this->controller = $controller;
    }

    public function inject(string $property, object $obj): void
    {
        if (property_exists($this->controller, $property)) {
            $message = get_class($this->controller).'::$'.$property.' already exists';
            DebugLog::log(__METHOD__, $message);

            return;
        }

        $this->controller->$property = $obj;
    }

    public function injectMultiple(array $instances): void
    {
        foreach ($instances as $property => $instance) {
            $this->inject($property, $instance);
        }
    }
}
