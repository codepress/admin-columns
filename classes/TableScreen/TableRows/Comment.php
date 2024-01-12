<?php

declare(strict_types=1);

namespace AC\TableScreen\TableRows;

use AC\Request;
use AC\TableScreen\TableRows;

final class Comment extends TableRows
{

    public function register(): void
    {
        add_action('parse_comment_query', [$this, 'handle_request']);
    }

    public function handle_request(): void
    {
        remove_action('parse_comment_query', [$this, __FUNCTION__]);

        parent::handle(new Request());
    }

}