<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\Type\Url\Site;

final class SeoPress extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-seopress',
            'SeoPress',
            'assets/images/addons/seopress.svg',
            sprintf(
                '%s %s',
                sprintf(
                    __('Integrates %s with Admin Columns.', 'codepress-admin-columns'),
                    'RankMath'
                ),
                sprintf(
                    __(
                        'Easily manage your SeoPress content with powerful tools for displaying, editing, exporting, filtering, and sorting',
                        'codepress-admin-columns'
                    ),
                    'SeoPress'
                )
            ),
            null,
            new Site(Site::PAGE_ADDON_SEOPRESS)
        );
    }

    public function is_plugin_active(): bool
    {
        return defined('SEOPRESS_PRO_VERSION');
    }

    public function show_notice(Screen $screen): bool
    {
        // RankMath prevents messages/logs from loading on their settings page
        return false;
    }

}