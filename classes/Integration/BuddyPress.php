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
            __(
                'Add BuddyPress profile fields, registration dates, and group data to your member and group tables. Filter by any field to find exactly the members you need.',
                'codepress-admin-columns'
            ),
            null,
            new Site(Site::PAGE_ADDON_BUDDYPRESS)
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