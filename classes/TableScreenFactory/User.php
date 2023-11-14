<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use WP_Screen;

class User implements TableScreenFactory
{

    public function create(ListKey $key): TableScreen
    {
        return new TableScreen\User($key);
    }

    public function can_create(ListKey $key): bool
    {
        return $key->equals(new ListKey('wp-users'));
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return $this->create(new ListKey('wp-users'));
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'users' === $screen->base && 'users' === $screen->id && 'delete' !== filter_input(INPUT_GET, 'action');
    }

}