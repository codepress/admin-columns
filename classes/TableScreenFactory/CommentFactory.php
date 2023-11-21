<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\Column;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use WP_Screen;

class CommentFactory implements TableScreenFactory
{

    public function create(ListKey $key): TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create(ListKey $key): bool
    {
        return $key->equals(new ListKey('wp-comments'));
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'edit-comments' === $screen->base && 'edit-comments' === $screen->id;
    }

    protected function create_table_screen(): TableScreen\Comment
    {
        return new TableScreen\Comment(
            [
                Column\CustomField::class,
                Column\Actions::class,
                Column\Comment\Agent::class,
                Column\Comment\Approved::class,
                Column\Comment\Author::class,
                Column\Comment\AuthorAvatar::class,
                Column\Comment\AuthorEmail::class,
                Column\Comment\AuthorIP::class,
                Column\Comment\AuthorName::class,
                Column\Comment\AuthorUrl::class,
                Column\Comment\Comment::class,
                Column\Comment\Date::class,
                Column\Comment\DateGmt::class,
                Column\Comment\Excerpt::class,
                Column\Comment\ID::class,
                Column\Comment\Post::class,
                Column\Comment\ReplyTo::class,
                Column\Comment\Response::class,
                Column\Comment\Status::class,
                Column\Comment\Type::class,
                Column\Comment\User::class,
                Column\Comment\WordCount::class,
            ]
        );
    }

}