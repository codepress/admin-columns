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
            __(
                'Show your Pods fields as list table columns. Inline edit field values, filter by any Pod field, and keep your custom content types organized at scale.',
                'codepress-admin-columns'
            ),
            null,
            new Site(Site::PAGE_ADDON_PODS)
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