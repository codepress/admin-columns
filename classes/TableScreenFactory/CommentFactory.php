<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\TableScreen;
use AC\TableScreen\Comment;
use AC\TableScreenFactory;
use AC\Type\TableId;
use WP_Screen;

class CommentFactory implements TableScreenFactory
{

    public function create(TableId $id): TableScreen
    {
        return $this->create_table_screen();
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create(TableId $id): bool
    {
        return $id->equals(new TableId('wp-comments'));
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'edit-comments' === $screen->base && 'edit-comments' === $screen->id;
    }

    protected function create_table_screen(): Comment
    {
        return new Comment();
    }

}