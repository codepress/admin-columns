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
            __(
                'Bring Toolset custom fields and post relationships into the list table. Sort and filter by any Toolset field, and edit values directly without switching screens.',
                'codepress-admin-columns'
            ),
            null,
            new Site(Site::PAGE_ADDON_TOOLSET_TYPES)
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