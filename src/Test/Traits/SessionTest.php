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

namespace OpenDesa\Legacy\Test\Traits;

use CodeIgniter\Session\Handlers\ArrayHandler;
use CodeIgniter\Session\SessionInterface;
use CodeIgniter\Test\Mock\MockSession;
use Config\Services;

trait SessionTest
{
    /** @var SessionInterface */
    protected $mockSession;

    /**
     * @before
     */
    public function reset(): void
    {
        $this->initGlobalSession();

        $this->resetServices();
        $this->resetInstance();
    }

    private function initGlobalSession()
    {
        $_SESSION = $_SESSION ?? [];
    }

    /**
     * Pre-loads the mock session driver into $this->session.
     *
     * @before
     */
    public function mockSession(): void
    {
        $config = config('App');
        $this->mockSession = new MockSession(
            new ArrayHandler($config, '0.0.0.0'),
            $config
        );
        Services::injectMock('session', $this->mockSession);
    }
}
