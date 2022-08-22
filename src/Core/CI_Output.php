<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Kenji Suzuki
 * Copyright (c) 2022 Agung Sugiarto
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/kenjis/ci3-to-4-upgrade-helper
 * @see https://github.com/agungsugiarto/legacy-to-laravel
 */

namespace Fluent\Legacy\Core;

use Config\Services;
use Fluent\Legacy\Exception\NotSupportedException;

class CI_Output
{
    /**
     * Set Header
     *
     * Lets you set a server header which will be sent with the final output.
     *
     * Note: If a file is cached, headers will not be sent.
     *
     * @param   string $header  Header
     * @param   bool   $replace Whether to replace the old header value, if already set
     *
     * @return  CI_Output
     *
     * @todo    We need to figure out how to permit headers to be cached.
     */
    public function set_header(string $header, bool $replace = true): self
    {
        $response = Services::response();

        [$name, $value] = explode(':', $header, 2);

        if ($replace) {
            $response->setHeader($name, trim($value));

            return $this;
        }

        $response->appendHeader($name, trim($value));

        return $this;
    }

    /**
     * Get Output
     *
     * Returns the current output string.
     *
     * @return  string
     */
    public function get_output(): string
    {
        $response = Services::response();

        return $response->getBody();
    }

    /**
     * Enable/disable Profiler
     *
     * @return  CI_Output
     */
    public function enable_profiler(): self
    {
        throw new NotSupportedException(
            'enable_profiler() is not supported.'
            .' In CI4 Debug Toolbar is enabled by default not in production.'
            .' See <https://codeigniter4.github.io/CodeIgniter4/testing/debugging.html#the-debug-toolbar>'
        );
    }
}
