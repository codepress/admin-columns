<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactoryDefinitionCollection;
use AC\PostType;
use AC\TableScreen;
use AC\Taxonomy;
use AC\Vendor\DI\Container;

abstract class BaseFactory implements ColumnFactories
{

    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): Collection\ColumnFactories
    {
        $collection = new Collection\ColumnFactories();
        $factories = $this->get_factories($table_screen);

        if ($factories->count()) {
            $parameters = [
                'list_key'  => $table_screen->get_key(),
                'meta_type' => $table_screen instanceof TableScreen\MetaType ? $table_screen->get_meta_type() : null,
                'post_type' => $table_screen instanceof PostType ? $table_screen->get_post_type() : null,
                'taxonomy'  => $table_screen instanceof Taxonomy ? $table_screen->get_taxonomy() : null,
            ];

            foreach ($this->get_factories($table_screen) as $factory) {
                $parameters += $factory->get_parameters();

                $instance = $this->container->make(
                    $factory->get_factory(),
                    $parameters
                );

                $collection->add(
                    $instance->get_column_type(),
                    $instance
                );
            }
        }

        return $collection;
    }

    abstract protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection;

}