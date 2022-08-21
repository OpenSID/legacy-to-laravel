<?php

namespace Fluent\Legacy\database;

use Exception;

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019 - 2022, CodeIgniter Foundation
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @copyright	Copyright (c) 2019 - 2022, CodeIgniter Foundation (https://codeigniter.com/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */

/**
 * Initialize the database
 *
 * @category  Database
 * @author    EllisLab Dev Team
 * @link      https://codeigniter.com/userguide3/database/
 *
 * @param  string|string[]  $params
 * @param  bool             $query_builder_override Determines if query builder should be used or not
 */
function &DB($params = '', $query_builder_override = null)
{
    // No DB specified yet? Beat them senseless...
    if (empty($params['dbdriver'])) {
        throw new Exception('You have not selected a database type to connect to.');
    }

    // Load the DB classes. Note: Since the query builder class is optional
    // we need to dynamically create a class that extends proper parent class
    // based on whether we're using the query builder class or not.
    if ($query_builder_override !== null) {
        $query_builder = $query_builder_override;
    }
    // Backwards compatibility work-around for keeping the
    // $active_record config variable working. Should be
    // removed in v3.1
    elseif (! isset($query_builder) && isset($active_record)) {
        $query_builder = $active_record;
    }

    require_once dirname(__FILE__).'/DB_driver.php';

    if (! isset($query_builder) or $query_builder === true) {
        require_once dirname(__FILE__).'/DB_query_builder.php';
        if (! class_exists(__NAMESPACE__.'\CI_DB', false)) {
            /**
             * CI_DB
             *
             * Acts as an alias for both CI_DB_driver and CI_DB_query_builder.
             *
             * @see    CI_DB_query_builder
             * @see    CI_DB_driver
             */
            class CI_DB extends CI_DB_query_builder
            {
            }
        }
    } elseif (! class_exists(__NAMESPACE__.'\CI_DB', false)) {
        /**
         * @ignore
         */
        class CI_DB extends CI_DB_driver
        {
        }
    }

    // Load the DB driver
    $driver_file = dirname(__FILE__).'/drivers/'.$params['dbdriver'].'/'.$params['dbdriver'].'_driver.php';

    if (! file_exists($driver_file)) {
        throw new Exception('Invalid DB driver');
    }

    require_once $driver_file;

    // Instantiate the DB adapter
    $driver = '\Fluent\Legacy\database\CI_DB_'.$params['dbdriver'].'_driver';
    $DB = new $driver($params);

    // Check for a subdriver
    if (! empty($DB->subdriver)) {
        $driver_file = dirname(__FILE__).'/drivers/'.$DB->dbdriver.'/subdrivers/'.$DB->dbdriver.'_'.$DB->subdriver.'_driver.php';

        if (file_exists($driver_file)) {
            require_once $driver_file;
            $driver = 'CI_DB_'.$DB->dbdriver.'_'.$DB->subdriver.'_driver';
            $DB = new $driver($params);
        }
    }

    $DB->initialize();

    return $DB;
}
