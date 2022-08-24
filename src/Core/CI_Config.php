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

namespace OpenDesa\Legacy\Core;

use OpenDesa\Legacy\Exception\NotImplementedException;
use OpenDesa\Legacy\Exception\RuntimeException;

class CI_Config
{
    /** @var array */
    private $config = [];

    /**
     * Load Config File.
     *
     * @param string $file            Configuration file name
     * @param bool   $use_sections    Whether configuration values should be loaded into their own section
     * @param bool   $fail_gracefully Whether to just return FALSE or display an error message
     *
     * @return bool TRUE if the file was loaded correctly or FALSE on failure
     */
    public function load(string $file = '', bool $use_sections = false, bool $fail_gracefully = false)
    {
        if ($fail_gracefully !== false) {
            throw new NotImplementedException(
                '$fail_gracefully is not implemented yet.'
            );
        }

        $config = config($file);

        if ($config === null) {
            throw new RuntimeException(
                'Cannot find Config class "'.$file.'".'
                .' Fix your config name.'
            );
        }

        if ($use_sections) {
            $this->config[$file] = isset($this->config[$file])
                ? array_merge($this->config[$file], $config)
                : $config;

            return true;
        }

        $this->config = array_merge($this->config, $config);

        return true;
    }

    /**
     * Fetch a config file item.
     *
     * @param string $item  Config item name
     * @param string $index Index name
     *
     * @return string|null The configuration item or NULL if the item doesn't exist
     */
    public function item(string $item, string $index = '')
    {
        if ($index == '') {
            return isset($this->config[$item]) ? $this->config[$item] : null;
        }

        return isset($this->config[$index], $this->config[$index][$item]) ? $this->config[$index][$item] : null;
    }

    /**
     * Set a config file item.
     *
     * @param string $item  Config item key
     * @param string $value Config item value
     *
     * @return void
     */
    public function set_item($item, $value)
    {
        $this->config[$item] = $value;
    }
}
