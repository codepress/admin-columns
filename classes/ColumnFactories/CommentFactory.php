<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\Comment;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class CommentFactory implements ColumnFactories
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        if ( ! $table_screen instanceof AC\TableScreen\Comment) {
            return null;
        }

        // Todo implement custom field
        $factoryClasses = [
            Comment\AgentFactory::class,
            Comment\ApprovedFactory::class,
            Comment\AuthorAvatarFactory::class,
            Comment\AuthorEmailFactory::class,
            Comment\AuthorIpFactory::class,
            Comment\AuthorNameFactory::class,
            Comment\AuthorUrlFactory::class,
            Comment\DateGmtFactory::class,
            Comment\ExcerptFactory::class,
            Comment\IdFactory::class,
            Comment\PostFactory::class,
            Comment\ReplyToFactory::class,
            Comment\StatusFactory::class,
            Comment\CommentTypeFactory::class,
            Comment\UserFactory::class,
            Comment\WordCountFactory::class,
        ];

        foreach ($factoryClasses as $factoryClass) {
            $factories[] = $this->container->make($factoryClass);
        }

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_column_type(), $factory);
        }

        return $collection;
    }

}