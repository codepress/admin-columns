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
            sprintf(
                '%s %s',
                sprintf(
                    __('Integrates %s with Admin Columns.', 'codepress-admin-columns'),
                    __('Yoast SEO', 'codepress-admin-columns')
                ),
                sprintf(
                    __(
                        'Display, inline- and bulk-edit, export, smart filter and sort your Yoast SEO contents on any admin list table.',
                        'codepress-admin-columns'
                    ),
                    __('Yoast SEO', 'codepress-admin-columns')
                )
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