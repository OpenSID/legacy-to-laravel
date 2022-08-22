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

namespace Fluent\Legacy\Core\Loader\ClassResolver;

use Fluent\Legacy\Core\Loader\InSubDir;

class ModelResolver
{
    use InSubDir;

    /** @var string */
    private $namespace = 'App\\Models';

    public function resolve(string $model): string
    {
        if ($this->isFQCN($model)) {
            return $model;
        }

        if ($this->inSubDir($model)) {
            $parts = explode('/', $model);

            foreach ($parts as $key => $part) {
                $parts[$key] = ucfirst($part);
            }

            return $this->namespace.'\\'.implode('\\', $parts);
        }

        return $this->namespace.'\\'.ucfirst($model);
    }

    private function isFQCN(string $model): bool
    {
        if (substr($model, 0, strlen($this->namespace)) === $this->namespace) {
            return true;
        }

        return false;
    }
}
