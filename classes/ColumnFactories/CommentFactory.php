<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\Comment;
use AC\MetaType;
use AC\TableScreen;
use AC\Type\ListKey;
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

        $this->container->set(MetaType::class, MetaType::create_comment_type());
        $this->container->set(ListKey::class, $table_screen->get_key());

        // TODO implement custom field, date, response, type
        $factoryClasses = [
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