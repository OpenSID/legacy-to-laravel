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

namespace OpenDesa\Legacy\Test\TestCase;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use OpenDesa\Legacy\Test\Traits\FeatureTest;
use OpenDesa\Legacy\Test\Traits\ResetInstance;
use OpenDesa\Legacy\Test\Traits\SessionTest;
use Kenjis\PhpUnitHelper\TestDouble;

class FeatureTestCase extends CIUnitTestCase
{
    use ResetInstance;
    use FeatureTest;
    use SessionTest;
    use TestDouble;
    use FeatureTestTrait;
    use DatabaseTestTrait;

    /**
     * Should run db migration?
     *
     * @var bool
     */
    protected $migrate = false;
}
