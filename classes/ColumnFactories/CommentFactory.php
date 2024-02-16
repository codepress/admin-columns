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
        //
        //        $factories[] = $this->container->make(
        //            CustomFieldFactory::class,
        //            [
        //                'custom_field_factory' => new Settings\Column\CustomFieldFactory(
        //                    new MetaType(MetaType::POST),
        //                    $this->container->get(CustomFieldTypeFactory::class)
        //                ),
        //            ]
        //        );

        $factories[] = $this->container->get(Comment\AgentFactory::class);
        $factories[] = $this->container->get(Comment\ApprovedFactory::class);

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_type(), $factory);
        }

        return $collection;
    }

}