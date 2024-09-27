<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\Collection\ColumnFactories;
use AC\ColumnFactoryCollectionFactory;
use AC\ColumnFactoryDefinitionCollection;
use AC\PostType;
use AC\TableScreen;
use AC\Taxonomy;
use AC\Type\TableScreenContext;
use AC\Vendor\DI\Container;

abstract class BaseFactory implements ColumnFactoryCollectionFactory
{

    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ColumnFactories
    {
        $collection = new Collection\ColumnFactories();

        $factories = $this->get_factories($table_screen);

        if ($factories->count()) {
            $defaults = [
                'list_key'  => $table_screen->get_key(),
                'meta_type' => $table_screen instanceof TableScreen\MetaType ? $table_screen->get_meta_type() : null,
                'post_type' => $table_screen instanceof PostType ? $table_screen->get_post_type() : null,
                'taxonomy'  => $table_screen instanceof Taxonomy ? $table_screen->get_taxonomy() : null,
            ];

            if ($table_screen instanceof TableScreen\MetaType) {
                $defaults['table_screen_context'] = TableScreenContext::from_table_screen($table_screen);
            }

            foreach ($factories as $factory) {
                $collection->add(
                    $this->container->make(
                        $factory->get_factory(),
                        array_merge($defaults, $factory->get_parameters())
                    )
                );
            }
        }

        return $collection;
    }

    abstract protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection;

}