<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Kenji Suzuki
 * Copyright (c) 2022 Agung Sugiarto.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/kenjis/ci3-to-4-upgrade-helper
 * @see https://github.com/agungsugiarto/legacy-to-laravel
 */

namespace OpenDesa\Legacy\Core\Loader;

use OpenDesa\Legacy\Database;
use OpenDesa\Legacy\Database\CI_DB;
use OpenDesa\Legacy\Database\CI_DB_forge;

class DatabaseLoader
{
    /** @var ControllerPropertyInjector */
    private $injector;

    /** @var CI_DB */
    private $db;

    /** @var CI_DB_forge */
    private $dbforge;

    public function __construct(ControllerPropertyInjector $injector)
    {
        require_once __DIR__.'../../../database/DB.php';

        $this->injector = $injector;
    }

    public function load($params = '', $return = false, $query_builder = null)
    {
        if (
            $return === false && $query_builder === null
            && isset($this->db)
        ) {
            return false;
        }

        if ($return) {
            return Database\DB($params, $query_builder);
        }

        if ($this->db === null) {
            $this->db = Database\DB($params, $query_builder);
            $this->injector->inject('db', $this->db);
        }

        return false;
    }

    public function loadDbForge(?object $db = null, bool $return = false)
    {
        $ci = &get_instance();
        if (!is_object($db) or !($db instanceof CI_DB)) {
            class_exists('CI_DB', false) or $this->load();
            $db = &$ci->db;
        }

        require_once(__DIR__ . DIRECTORY_SEPARATOR . "../../Database/DB_forge.php");
        require_once(__DIR__ . DIRECTORY_SEPARATOR . "../../Database/drivers/{$db->dbdriver}/{$db->dbdriver}_forge.php");

        if (!empty($db->subdriver)) {
            $driver_path = __DIR__ . DIRECTORY_SEPARATOR . "../../Database/drivers/{$db->dbdriver}/subdrivers/{$db->dbdriver}_{$db->subdriver}_forge.php";
            if (file_exists($driver_path)) {
                require_once($driver_path);
                $class = "\OpenDesa\Legacy\Database\CI_DB_{$db->dbdriver}_{$db->subdriver}_forge";
            }
        } else {
            $class = "\OpenDesa\Legacy\Database\CI_DB_{$db->dbdriver}_forge";
        }

        if ($return === true) {
            return new $class($db);
        }

        if ($this->dbforge === null) {
            $this->dbforge = new $class($db);
            $this->injector->inject('dbforge', $this->dbforge);
        }

        return $this;
    }
}
