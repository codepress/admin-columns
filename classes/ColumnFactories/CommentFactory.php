<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\ColumnFactory\Comment;
use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;

final class CommentFactory extends BaseFactory
{

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();

        if ( ! $table_screen instanceof AC\TableScreen\Comment) {
            return $collection;
        }

        $factories = [
            AC\ColumnFactory\ActionsFactory::class,
            AC\ColumnFactory\CustomFieldFactory::class,
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

        foreach ($factories as $factory) {
            $collection->add(new AC\Type\ColumnFactoryDefinition($factory));
        }

        return $collection;
    }

}