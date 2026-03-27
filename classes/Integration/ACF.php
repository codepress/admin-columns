<?php

namespace AC\Integration;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\External;
use AC\Type\Url\Site;

final class ACF extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-acf',
            __('Advanced Custom Fields', 'codepress-admin-columns'),
            'assets/images/addons/acf-v2.png',
            __('Manage ACF data directly from the list table.', 'codepress-admin-columns'),
            new External('https://www.advancedcustomfields.com'),
            new Site(Site::PAGE_ADDON_ACF),
            [
                __('Display nearly any ACF field as a column', 'codepress-admin-columns'),
                __('Sort and filter by custom field values', 'codepress-admin-columns'),
                __('Edit field data without opening each post', 'codepress-admin-columns'),
            ],
            __('Best for content teams managing posts, pages, or custom post types with structured field data.', 'codepress-admin-columns')
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('acf', false) || class_exists('ACF', false);
    }

    public function show_notice(Screen $screen): bool
    {
        return in_array($screen->get_id(), [
            'edit-acf-field-group',
            'acf-field-group',
        ]);
    }

}