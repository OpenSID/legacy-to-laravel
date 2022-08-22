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

use function get_instance;

/**
 * @property \Fluent\Legacy\Core\CI_Benchmark             $benchmark
 * @property \Fluent\Legacy\Core\CI_Config                $config
 * @property \Fluent\Legacy\database\CI_DB_query_builder  $db
 * @property \Fluent\Legacy\Core\CI_Input                 $input
 * @property \Fluent\Legacy\Core\CI_Lang                  $lang
 * @property \Fluent\Legacy\Core\CI_Loader                $loader
 * @property \Fluent\Legacy\Core\CI_log                   $log
 * @property \Fluent\Legacy\Core\CI_Output                $output
 * @property \Fluent\Legacy\Core\CI_Router                $router
 * @property \Fluent\Legacy\Core\CI_Security              $security
 * @property \Fluent\Legacy\Core\CI_Session               $session
 * @property \Fluent\Legacy\Core\CI_URI                   $uri
 * @property \Fluent\Legacy\Core\CI_Utf8                  $utf8
 */
class CI_Model
{
    public function __construct()
    {
    }

    public function __get($key)
    {
        // Debugging note:
        //  If you're here because you're getting an error message
        //  saying 'Undefined Property: system/core/Model.php', it's
        //  most likely a typo in your model code.
        return get_instance()->$key;
    }
}
