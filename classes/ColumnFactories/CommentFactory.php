<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\ColumnFactory\Comment;
use AC\TableScreen;

class CommentFactory extends BaseFactory
{

    protected function get_factories(TableScreen $table_screen): array
    {
        if ( ! $table_screen instanceof AC\TableScreen\Comment) {
            return [];
        }

        return [
            AC\ColumnFactory\CustomFieldFactory::class,
            AC\ColumnFactory\ActionsFactory::class,
            Comment\AgentFactory::class,
            Comment\ApprovedFactory::class,
            Comment\AuthorAvatarFactory::class,
            Comment\AuthorEmailFactory::class,
            Comment\AuthorIpFactory::class,
            Comment\AuthorNameFactory::class,
            Comment\AuthorUrlFactory::class,
            Comment\CommentTypeFactory::class,
            Comment\DateGmtFactory::class,
            Comment\ExcerptFactory::class,
            Comment\IdFactory::class,
            Comment\PostFactory::class,
            Comment\ReplyToFactory::class,
            Comment\StatusFactory::class,
            Comment\UserFactory::class,
            Comment\WordCountFactory::class,
        ];
    }

}