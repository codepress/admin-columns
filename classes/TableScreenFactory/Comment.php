<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use WP_Screen;

class Comment implements TableScreenFactory
{

    public function create(ListKey $key): TableScreen
    {
        return new TableScreen\Comment($key, 'edit-comments');
    }

    public function can_create(ListKey $key): bool
    {
        return $key->equals(new ListKey('wp-comments'));
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return new TableScreen\Comment(new ListKey('wp-comments'), 'edit-comments');
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'edit-comments' === $screen->base && 'edit-comments' === $screen->id;
    }

}