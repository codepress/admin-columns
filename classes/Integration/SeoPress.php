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
            __('Manage SEO data directly from the list table.', 'codepress-admin-columns'),
            null,
            new Site(Site::PAGE_ADDON_SEOPRESS),
            [
                __('Display SEO titles, descriptions, and scores as columns', 'codepress-admin-columns'),
                __('Filter content by SEO metadata', 'codepress-admin-columns'),
                __('Edit SEO fields without opening each post', 'codepress-admin-columns'),
            ],
            __('Best for content teams managing on-page SEO with SeoPress.', 'codepress-admin-columns')
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