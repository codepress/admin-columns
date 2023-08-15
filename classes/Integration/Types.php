<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\Type\Url\Site;

final class Types extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-types',
            __('Toolset Types', 'codepress-admin-columns'),
            'assets/images/addons/toolset-types.png',
            sprintf(
                '%s %s',
                sprintf(
                    __('Integrates %s with Admin Columns.', 'codepress-admin-columns'),
                    __('Toolset Types', 'codepress-admin-columns')
                ),
                sprintf(
                    __(
                        'Display, inline- and bulk-edit, export, smart filter and sort your %s contents on any admin list table.',
                        'codepress-admin-columns'
                    ),
                    __('Toolset Types', 'codepress-admin-columns')
                )
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
        return in_array($screen->get_id(), [
            'toplevel_page_pods',
            'pods-admin_page_pods-settings',
        ]);
    }

}