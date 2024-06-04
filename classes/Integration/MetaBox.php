<?php

namespace AC\Integration;

use AC\Integration;
use AC\ListScreen;
use AC\Screen;
use AC\Type\Url\Site;

final class MetaBox extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-metabox',
            __('Meta Box', 'codepress-admin-columns'),
            'assets/images/addons/metabox.svg',
            sprintf(
                '%s %s',
                sprintf(
                    __('Integrates %s with Admin Columns.', 'codepress-admin-columns'),
                    __('Meta Box', 'codepress-admin-columns')
                ),
                sprintf(
                    __(
                        'Display, inline- and bulk-edit, export, smart filter and sort your %s contents on any admin list table.',
                        'codepress-admin-columns'
                    ),
                    __('Meta Box', 'codepress-admin-columns')
                )
            ),
            new Site(Site::PAGE_ADDON_METABOX)
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('RWMB_Loader', false);
    }

    public function show_notice(Screen $screen): bool
    {
        return $screen->get_id() === 'edit-meta-box';
    }

    public function show_placeholder(ListScreen $list_screen): bool
    {
        return true;
    }

}