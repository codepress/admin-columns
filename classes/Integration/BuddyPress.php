<?php

namespace AC\Integration;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\Screen;
use AC\TableScreen;
use AC\Type\Integration;
use AC\Type\TableId;
use AC\Type\Url\Site;

final class BuddyPress extends Integration implements MenuGroupFactory
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-buddypress',
            __('BuddyPress', 'codepress-admin-columns'),
            'assets/images/addons/buddypress.png',
            __('Manage community data directly from the list table.', 'codepress-admin-columns'),
            null,
            new Site(Site::PAGE_ADDON_BUDDYPRESS),
            [
                __('Display profile fields and group data as columns', 'codepress-admin-columns'),
                __('Filter members by any BuddyPress field', 'codepress-admin-columns'),
                __('Edit member data without opening each profile', 'codepress-admin-columns'),
            ],
            __('Best for community managers overseeing members and groups.', 'codepress-admin-columns')
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('BuddyPress', false);
    }

    public function show_notice(Screen $screen): bool
    {
        return 'users' === $screen->get_id();
    }

    public function show_placeholder(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\User;
    }

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        $table_id = $table_screen->get_id();

        if (
            $table_id->equals(new TableId('bp-groups')) ||
            $table_id->equals(new TableId('bp-activity')) ||
            $table_id->equals(new TableId('bp-email'))
        ) {
            return new MenuGroup(
                'buddypress',
                __('BuddyPress'),
                22,
                'dashicons-buddicons-buddypress-logo'
            );
        }

        if (
            $table_id->equals(new TableId('wp-taxonomy_bp_member_type')) ||
            $table_id->equals(new TableId('wp-taxonomy_bp_group_type'))
        ) {
            return new MenuGroup(
                'buddypress',
                __('BuddyPress'),
                24,
                'dashicons-buddicons-buddypress-logo'
            );
        }

        return null;
    }

}