<?php

declare(strict_types=1);

namespace AC\TableScreen\TableRows;

use AC\Request;
use AC\TableScreen\TableRows;

final class Post extends TableRows
{

    public function register(): void
    {
        add_action('edit_posts_per_page', [$this, 'handle_request']);
    }

    public function handle_request(): void
    {
        remove_action('edit_posts_per_page', [$this, __FUNCTION__]);

        parent::handle(new Request());
    }

}