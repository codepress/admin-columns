<?php

namespace AC\Integration;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class SeoPress extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-seopress',
            'SeoPress',
            'assets/images/addons/seopress.svg',
            __(
                'Add SeoPress titles, descriptions, and scores to your post list table. Spot missing metadata at a glance and fix it inline - no need to open each post.',
                'codepress-admin-columns'
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
        return in_array($screen->get_id(), [
            'toplevel_page_seopress-option',
            'seo_page_seopress-titles',
        ]);
    }

}