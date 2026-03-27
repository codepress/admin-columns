<?php

namespace AC\Integration;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class Pods extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-pods',
            __('Pods', 'codepress-admin-columns'),
            'assets/images/addons/pods.png',
            __('Manage Pods data directly from the list table.', 'codepress-admin-columns'),
            null,
            new Site(Site::PAGE_ADDON_PODS),
            [
                __('Display Pod fields as sortable columns', 'codepress-admin-columns'),
                __('Filter content by any Pod field value', 'codepress-admin-columns'),
                __('Edit field values without switching screens', 'codepress-admin-columns'),
            ],
            __('Best for developers managing custom content types with Pods.', 'codepress-admin-columns')
        );
    }

    public function is_plugin_active(): bool
    {
        return function_exists('pods');
    }

    public function show_notice(Screen $screen): bool
    {
        return in_array($screen->get_id(), [
            'toplevel_page_pods',
            'pods-admin_page_pods-settings',
        ]);
    }

}