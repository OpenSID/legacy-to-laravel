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

use CodeIgniter\Pager\Pager;
use Config\Pager as PagerConfig;
use Config\Services;
use OpenDesa\Legacy\Exception\NotSupportedException;

class CI_Pagination
{
    /** @var Pager */
    private $pager;

    /** @var PagerConfig */
    private $pagerConfig;

    /**
     * Constructor.
     *
     * @param PagerConfig|array|null $params Initialization parameters
     *
     * @return void
     */
    public function __construct($params = null)
    {
        helper('url');

        $this->pagerConfig = new PagerConfig();

        if (is_array($params)) {
            $this->initialize($params);

            return;
        }

        if ($params instanceof PagerConfig) {
            $this->pagerConfig = $params;
        }

        $this->pager = Services::pager($this->pagerConfig);
    }

    /**
     * Initialize Preferences.
     *
     * @param array $params Initialization parameters
     *
     * @return CI_Pagination
     */
    public function initialize(array $params = []): self
    {
        $this->pagerConfig->perPage = $params['per_page'];

        $this->checkUnsupportedConfigs($params);

        foreach ($params as $property => $value) {
            $this->pagerConfig->$property = $value;
        }

        $this->pager = Services::pager($this->pagerConfig);

        return $this;
    }

    private function checkUnsupportedConfigs(array $params): void
    {
        $customizingLinkConfigs = [
            'full_tag_open',
            'full_tag_close',
            'first_link',
            'first_tag_open',
            'first_tag_close',
            'first_url',
            'last_link',
            'last_tag_open',
            'last_tag_close',
            'next_link',
            'next_tag_open',
            'next_tag_close',
            'prev_link',
            'prev_tag_open',
            'prev_tag_close',
            'cur_tag_open',
            'cur_tag_close',
            'num_tag_open',
            'num_tag_close',
            'display_pages',
            'attributes',
        ];

        $unsupportedConfigs = [];

        foreach (array_keys($params) as $property) {
            if (in_array($property, $customizingLinkConfigs, true)) {
                $unsupportedConfigs[] = $property;
            }
        }

        if ($unsupportedConfigs !== []) {
            throw new NotSupportedException(
                'You can not customize Pagination Links by config '
                .implode(', ', $unsupportedConfigs).'.'
                .' Create your own templates, and configure to use them.'
                .' See <https://github.com/kenjis/ci3-to-4-upgrade-helper/blob/1.x/docs/HowToUpgradeFromCI3ToCI4.md#pagination>.'
            );
        }
    }

    /**
     * Generate the pagination links.
     */
    public function create_links(): string
    {
        $this->pager->setSegment($this->pagerConfig->uri_segment ?? 3);

        return $this->pager->makeLinks(
            $this->pager->getCurrentPage(),
            $this->pagerConfig->perPage,
            $this->pagerConfig->total_rows
        );
    }
}
