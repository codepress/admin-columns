<?php

namespace AC\Integration;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class Types extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-types',
            __('Toolset Types', 'codepress-admin-columns'),
            'assets/images/addons/toolset-types.png',
            __('Manage Toolset data directly from the list table.', 'codepress-admin-columns'),
            null,
            new Site(Site::PAGE_ADDON_TOOLSET_TYPES),
            [
                __('Display custom fields and relationships as columns', 'codepress-admin-columns'),
                __('Sort and filter by any Toolset field', 'codepress-admin-columns'),
                __('Edit values directly without switching screens', 'codepress-admin-columns'),
            ],
            __('Best for sites built with Toolset custom fields and post relationships.', 'codepress-admin-columns')
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('Types_Main', false);
    }

    public function show_notice(Screen $screen): bool
    {
        return $screen->is_screen('edit-custom-toolset-type');
    }

}