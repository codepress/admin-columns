<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\Column;
use AC\Column\CustomField;
use AC\ColumnTypeCollection;
use AC\MetaType;
use AC\TableScreen;

class CommentFactory implements AC\ColumnTypesFactory
{

    protected function can_create(TableScreen $table_screen): bool
    {
        return $table_screen->get_key()->equals(new AC\Type\ListKey('wp-comments'));
    }

    public function create(TableScreen $table_screen): ?ColumnTypeCollection
    {
        if ( ! $this->can_create($table_screen)) {
            return null;
        }

        return $this->get_columns();
    }

    private function get_columns(): ColumnTypeCollection
    {
        $collection = ColumnTypeCollection::from_list([
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
        ]);

        $collection->add(new CustomField(new MetaType(MetaType::COMMENT)));

        return $collection;
    }

}