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

class LibraryResolver
{
    use InSubDir;

    /** @var string */
    private $ci3LibraryNamespace = 'Fluent\Legacy\Library';

    /** @var string[] */
    private $ci3Libraries = [
        'cache',
        'email',
        'encryption',
        'form_validation',
        'image_lib',
        'pagination',
        'parser',
        'session',
        'upload',
        'user_agent',
    ];

    /** @var string */
    private $prefix = 'CI_';

    /** @var string */
    private $userLibraryNamespace = 'App\Libraries';

    public function resolve(string $library): string
    {
        $classname = $this->resolveCI3Library($library);

        if ($classname === null) {
            $classname = $this->resolveUserLibrary($library);
        }

        return $classname;
    }

    private function resolveCI3Library(string $library): ?string
    {
        $classname = $this->prefix.ucfirst($library);
        if (in_array($library, $this->ci3Libraries, true)) {
            return $this->ci3LibraryNamespace.'\\'.$classname;
        }

        return null;
    }

    private function resolveUserLibrary(string $library): string
    {
        if ($this->inSubDir($library)) {
            $parts = explode('/', $library);

            foreach ($parts as $key => $part) {
                $parts[$key] = ucfirst($part);
            }

            return $this->userLibraryNamespace.'\\'.implode('\\', $parts);
        }

        return $this->userLibraryNamespace.'\\'.ucfirst($library);
    }
}
