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
            __(
                'See your SEO data where you manage your content. View focus keywords, SEO scores, and schema status as columns - then filter and sort to find pages that need attention.',
                'codepress-admin-columns'
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