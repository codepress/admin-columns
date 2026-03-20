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
            __(
                'See every ACF field directly in your list table - and edit values without opening a single post. Sort, filter, and bulk edit across all field types including repeaters, groups, and relationships.',
                'codepress-admin-columns'
            ),
            new External('https://www.advancedcustomfields.com'),
            new Site(Site::PAGE_ADDON_ACF)
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