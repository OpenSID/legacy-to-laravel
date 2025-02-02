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

namespace OpenDesa\Legacy\Library;

use CodeIgniter\HTTP\UserAgent;
use Config\Services;

class CI_User_agent
{
    /** @var UserAgent */
    private $agent;

    /**
     * Constructor.
     *
     * Sets the User Agent and runs the compilation routine
     *
     * @return void
     */
    public function __construct()
    {
        $request = Services::request();
        $this->agent = $request->getUserAgent();
    }

    /**
     * Is Mobile.
     *
     * @param string $key
     */
    public function is_mobile(?string $key = null): bool
    {
        return $this->agent->isMobile($key);
    }
}
