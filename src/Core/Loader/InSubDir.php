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

trait InSubDir
{
    private function inSubDir(string $component): bool
    {
        return strrpos($component, '/') !== false;
    }
}
