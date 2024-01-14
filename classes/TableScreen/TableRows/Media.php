<?php

declare(strict_types=1);

namespace AC\TableScreen\TableRows;

use AC\Request;
use AC\TableScreen\TableRows;
use WP_Query;

final class Media extends TableRows
{

    public function register(): void
    {
        add_action('pre_get_posts', [$this, 'pre_handle_request']);
    }

    public function pre_handle_request(WP_Query $query): void
    {
        if ( ! $query->is_main_query()) {
            return;
        }

        remove_action('pre_get_posts', [$this, __FUNCTION__]);

        parent::handle(new Request());
    }

}