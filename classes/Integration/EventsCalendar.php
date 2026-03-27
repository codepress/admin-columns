<?php

namespace AC\Integration;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\PostType;
use AC\Screen;
use AC\TableScreen;
use AC\TableScreen\Post;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class EventsCalendar extends Integration implements MenuGroupFactory
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-events-calendar',
            __('Events Calendar', 'codepress-admin-columns'),
            'assets/images/addons/events-calendar.png',
            __('Manage event data directly from the list table.', 'codepress-admin-columns'),
            null,
            new Site(Site::PAGE_ADDON_EVENTS_CALENDAR),
            [
                __('Display event dates, venues, and organizers as columns', 'codepress-admin-columns'),
                __('Sort and filter events by any field', 'codepress-admin-columns'),
                __('Edit event details without opening each event', 'codepress-admin-columns'),
            ],
            __('Best for teams organizing events, venues, and schedules.', 'codepress-admin-columns')
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('Tribe__Events__Main');
    }

    private function get_post_types(): array
    {
        return [
            'tribe_events',
            'tribe_organizer',
            'tribe_venue',
            'tribe_event_series',
        ];
    }

    public function show_notice(Screen $screen): bool
    {
        return 'edit' === $screen->get_base()
               && in_array($screen->get_post_type(), $this->get_post_types());
    }

    public function show_placeholder(TableScreen $table_screen): bool
    {
        return $table_screen instanceof PostType && in_array(
                (string)$table_screen->get_post_type(),
                $this->get_post_types()
            );
    }

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if (
            $table_screen instanceof Post &&
            in_array((string)$table_screen->get_post_type(), $this->get_post_types(), true)
        ) {
            return new MenuGroup(
                'events-calendar',
                __('Events Calendar', 'codepress-admin-columns'),
                14,
                'dashicons-calendar-alt'
            );
        }

        return null;
    }

}