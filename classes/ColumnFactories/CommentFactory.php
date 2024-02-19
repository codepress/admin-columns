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

        $factories[] = $this->container->get(Comment\AgentFactory::class);
        $factories[] = $this->container->get(Comment\ApprovedFactory::class);
        $factories[] = $this->container->get(Comment\AuthorAvatar::class);
        $factories[] = $this->container->get(Comment\AuthorIpFactory::class);
        $factories[] = $this->container->get(Comment\AuthorNameFactory::class);
        $factories[] = $this->container->get(Comment\AuthorUrlFactory::class);
        $factories[] = $this->container->get(Comment\DateGmtFactory::class);
        $factories[] = $this->container->get(Comment\ExcerptFactory::class);
        $factories[] = $this->container->get(Comment\IdFactory::class);
        $factories[] = $this->container->get(Comment\PostFactory::class);
        $factories[] = $this->container->get(Comment\ReplyToFactory::class);
        $factories[] = $this->container->get(Comment\StatusFactory::class);
        $factories[] = $this->container->get(Comment\CommentTypeFactory::class);
        $factories[] = $this->container->get(Comment\UserFactory::class);
        $factories[] = $this->container->get(Comment\WordCountFactory::class);

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_type(), $factory);
        }

        return $collection;
    }

}