<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\TableScreen;
use AC\TableScreen\User;
use AC\TableScreenFactory;
use AC\Type\TableId;
use WP_Screen;

class UserFactory implements TableScreenFactory
{

    public function create(TableId $id): TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create(TableId $id): bool
    {
        return $id->equals(new TableId('wp-users'));
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return $this->create(new TableId('wp-users'));
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'users' === $screen->base && 'users' === $screen->id && 'delete' !== filter_input(INPUT_GET, 'action');
    }

    protected function create_table_screen(): User
    {
        return new User();
    }

}