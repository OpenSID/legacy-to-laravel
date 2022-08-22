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

namespace Fluent\Legacy\Core;

use function array_change_key_case;
use function array_keys;
use const CASE_LOWER;
use Fluent\Legacy\Core\Loader\ClassResolver\CoreResolver;
use Fluent\Legacy\Core\Loader\ControllerPropertyInjector;
use Fluent\Legacy\Internal\DebugLog;
use function get_class;
use ReflectionObject;
use function strtolower;

class CoreLoader
{
    /** @var CoreLoader */
    private static $instance;

    /** @var array */
    private $coreClasses = [
        // name w/o prefix => instance
        'Benchmark' => null,
        'Config' => null,
        'Input' => null,
        'Lang' => null,
        'Log' => null,
        'Output' => null,
        'Security' => null,
        'URI' => null,
        'Utf8' => null,
    ];

    /** @var CoreResolver */
    private $resolver;

    /** @var CI_Loader */
    private $loader;

    public function __construct()
    {
        self::$instance = $this;

        $this->resolver = new CoreResolver();

        $this->load();
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    private function load(): void
    {
        foreach (array_keys($this->coreClasses) as $class) {
            $classname = $this->resolver->resolve($class);
            $obj = new $classname();
            $this->coreClasses[$class] = $obj;
        }
    }

    /**
     * Inject Core Classes w/o CI_Loader to Controller
     */
    public function injectToController(ControllerPropertyInjector $injector): void
    {
        $array = array_change_key_case($this->coreClasses, CASE_LOWER);
        $injector->injectMultiple($array);
    }

    /**
     * Inject Core Classes
     */
    public function injectTo(object $obj): void
    {
        $reflection = new ReflectionObject($obj);
        $classname = get_class($obj);

        foreach ($this->coreClasses as $name => $instance) {
            $property = strtolower($name);

            // Skip if the property exists
            if (! $reflection->hasProperty($property)) {
                $obj->$property = $instance;

                $message = $classname.'::$'.$property.' injected';
                DebugLog::log(__METHOD__, $message);
            } else {
                $message = $classname.'::$'.$property.' already exists';
                DebugLog::log(__METHOD__, $message);
            }
        }

        if (! $reflection->hasProperty('load')) {
            $obj->load = $this->loader;
        }
    }

    public function setLoader(CI_Loader $loader): void
    {
        $this->loader = $loader;
    }
}
