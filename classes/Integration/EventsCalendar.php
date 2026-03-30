<?php

namespace AC\Integration;

use AC\PostType;
use AC\Screen;
use AC\TableScreen;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class EventsCalendar extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-events-calendar',
            __('Events Calendar', 'codepress-admin-columns'),
            'assets/images/addons/events-calendar.png',
            __(
                'See event dates, venues, organizers, and ticket data at a glance. Sort by date, filter by venue, and bulk edit event details without opening each event individually.',
                'codepress-admin-columns'
            ),
            null,
            new Site(Site::PAGE_ADDON_EVENTS_CALENDAR)
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

}