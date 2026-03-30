<?php

namespace AC\Integration;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class YoastSeo extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-yoast-seo',
            'Yoast SEO',
            'assets/images/addons/yoast-seo.png',
            __(
                'View Yoast focus keywords, readability scores, and SEO status directly in the list table. Filter by score to find underperforming content, then update SEO fields inline.',
                'codepress-admin-columns'
            ),
            null,
            new Site(Site::PAGE_ADDON_YOAST_SEO)
        );
    }

    public function is_plugin_active(): bool
    {
        return defined('WPSEO_VERSION');
    }

    public function show_notice(Screen $screen): bool
    {
        return in_array($screen->get_id(), [
            'toplevel_page_wpseo_dashboard',
            'seo_page_wpseo_titles',
        ]);
    }

}