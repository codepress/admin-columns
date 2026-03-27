<?php

namespace AC\Integration;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class RankMath extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-rankmath',
            'RankMath',
            'assets/images/addons/rank-math.svg',
            __('Manage SEO data directly from the list table.', 'codepress-admin-columns'),
            null,
            new Site(Site::PAGE_ADDON_RANK_MATH),
            [
                __('Display focus keywords and SEO scores as columns', 'codepress-admin-columns'),
                __('Filter content by SEO score or status', 'codepress-admin-columns'),
                __('Spot underperforming pages at a glance', 'codepress-admin-columns'),
            ],
            __('Best for content teams optimizing search rankings with RankMath.', 'codepress-admin-columns')
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