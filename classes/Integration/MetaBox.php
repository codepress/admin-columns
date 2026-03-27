<?php

namespace AC\Integration;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\PostType;
use AC\Screen;
use AC\TableScreen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class MetaBox extends Integration implements MenuGroupFactory
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-metabox',
            __('Meta Box', 'codepress-admin-columns'),
            'assets/images/addons/metabox.svg',
            __('Manage Meta Box data directly from the list table.', 'codepress-admin-columns'),
            null,
            new Site(Site::PAGE_ADDON_METABOX),
            [
                __('Display Meta Box fields as sortable columns', 'codepress-admin-columns'),
                __('Filter and sort by any custom field', 'codepress-admin-columns'),
                __('Bulk update field values from a single overview', 'codepress-admin-columns'),
            ],
            __('Best for developers managing structured content with Meta Box.', 'codepress-admin-columns')
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

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if (
            $table_screen instanceof PostType &&
            in_array(
                (string)$table_screen->get_post_type(),
                ['meta-box', 'mb-taxonomy', 'mb-relationship', 'mb-post-type'],
                true
            )
        ) {
            return new MenuGroup('metabox', 'MetaBox', 14);
        }

        return null;
    }

}