<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class EventsCalendarNotice implements IntegrationNotice
{

    public function is_active(Screen $screen): bool
    {
        if ( ! class_exists('Tribe__Events__Main')) {
            return false;
        }

        return 'edit' === $screen->get_base()
               && in_array($screen->get_post_type(), ['tribe_events', 'tribe_organizer', 'tribe_venue'], true);
    }

    public function get_slug(): string
    {
        return 'events-calendar';
    }

    public function get_integration_slug(): string
    {
        return 'ac-addon-events-calendar';
    }

    public function get_icon(): string
    {
        return '📅';
    }

    public function get_eyebrow(): string
    {
        return __('Admin Columns Pro for The Events Calendar', 'codepress-admin-columns');
    }

    public function get_title(): string
    {
        return __('Still opening each event to check dates, venues, and organizers?', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('See every event detail at a glance - filter by date range, venue, or status, and edit events inline from this table.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return sprintf('%s - %s', __('Upgrade', 'codepress-admin-columns'), '€79/year');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_EVENTS_CALENDAR), 'notice-events-calendar'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_EVENTS_CALENDAR), 'notice-events-calendar-features'))->get_url();
    }

}
