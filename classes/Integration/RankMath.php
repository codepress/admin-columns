<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\Type\Url\Site;

final class RankMath extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-rankmath',
            'RankMath',
            'assets/images/addons/rank-math.svg',
            sprintf(
                '%s %s',
                sprintf(
                    __('Integrates %s with Admin Columns.', 'codepress-admin-columns'),
                    'RankMath'
                ),
                sprintf(
                    __(
                        'Easily manage your Rank Math SEO content with powerful tools for displaying, editing, exporting, filtering, and sorting',
                        'codepress-admin-columns'
                    ),
                    'RankMath'
                )
            ),
            null,
            new Site(Site::PAGE_ADDON_RANK_MATH)
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('RankMath');
    }

    public function show_notice(Screen $screen): bool
    {
        // RankMath prevents messages/logs from loading on their settings page
        return false;
    }

}