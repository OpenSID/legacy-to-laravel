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

use Illuminate\Support\Facades\File;

if (! function_exists('force_download')) {
    /**
     * Force Download
     *
     * Generates headers that force a download to happen
     *
     * @param	mixed	filename
     * @param	mixed	file path to be downloaded
     * @param	bool	whether to try and send the actual file MIME type
     * @return	void
     */
    function force_download($filename = '', $data = '', $set_mime = false)
    {
        $header = [];

        if ($set_mime === true) {
            $mime = File::mimeType($filename);

            $header = ['content-type' => $mime];
        }

        return response()->download($data, $filename, $header);
    }
}
