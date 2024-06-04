<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\Type\Url\Site;

final class EventsCalendar extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-events-calendar',
            __('Events Calendar', 'codepress-admin-columns'),
            'assets/images/addons/events-calendar.png',
            sprintf(
                '%s %s',
                sprintf(
                    __('Integrates %s with Admin Columns.', 'codepress-admin-columns'),
                    __('Events Calendar', 'codepress-admin-columns')
                ),
                __(
                    'Display, inline- and bulk-edit, export, smart filter and sort your Events, Organizers and Venues.',
                    'codepress-admin-columns'
                )
            ),
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
        ];
    }

    public function show_notice(Screen $screen): bool
    {
        return 'edit' === $screen->get_base()
               && in_array($screen->get_post_type(), $this->get_post_types(), true);
    }

}