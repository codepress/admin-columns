<?php

declare(strict_types=1);

namespace AC\TableScreen\TableRows;

use AC\Request;
use AC\TableScreen\TableRows;

final class User extends TableRows
{

    public function register(): void
    {
        add_action('users_list_table_query_args', [$this, 'handle_request']);
    }

    public function handle_request(): void
    {
        remove_action('users_list_table_query_args', [$this, __FUNCTION__]);

        parent::handle(new Request());
    }

}