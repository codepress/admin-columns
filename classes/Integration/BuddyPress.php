<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\TableScreen;
use AC\Type\Url\Site;

final class BuddyPress extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-buddypress',
            __('BuddyPress', 'codepress-admin-columns'),
            'assets/images/addons/buddypress.png',
            sprintf(
                '%s %s',
                sprintf(
                    __('Integrates %s with Admin Columns.', 'codepress-admin-columns'),
                    __('BuddyPress', 'codepress-admin-columns')
                ),
                __(
                    'Display, inline- and bulk-edit, export, smart filter and sort your BuddyPress data fields on the Users page.',
                    'codepress-admin-columns'
                )
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

}