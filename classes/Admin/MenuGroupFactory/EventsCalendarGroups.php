<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\TableScreen;
use AC\TableScreen\Post;

class EventsCalendarGroups implements MenuGroupFactory
{

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        if (
            $table_screen instanceof Post &&
            in_array(
                (string)$table_screen->get_post_type(),
                ['tribe_events', 'tribe_organizer', 'tribe_venue', 'tribe_event_series'],
                true
            )
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
