<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\ColumnFactories;
use AC\PostType;
use AC\TableScreen;
use AC\Taxonomy;
use AC\Type\TableScreenContext;
use AC\Vendor\DI\Container;

abstract class BaseFactory implements ColumnFactories
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): Collection\ColumnFactories
    {
        $defaults = [
            'list_key'  => $table_screen->get_key(),
            'context'   => TableScreenContext::from_table_screen($table_screen),
            'meta_type' => $table_screen instanceof TableScreen\MetaType ? $table_screen->get_meta_type() : null,
            'post_type' => $table_screen instanceof PostType ? $table_screen->get_post_type() : null,
            'taxonomy'  => $table_screen instanceof Taxonomy ? $table_screen->get_taxonomy() : null,
        ];

        $collection = new Collection\ColumnFactories();

        foreach ($this->get_factories($table_screen) as $factory => $parameters) {
            // Allow for lazy definition
            if (is_numeric($factory)) {
                $factory = $parameters;
                $parameters = [];
            }

            $parameters += $defaults;

            $instance = $this->container->make($factory, $parameters);

            $collection->add(
                $instance->get_column_type(),
                $instance
            );
        }

        return $collection;
    }

    abstract protected function get_factories(TableScreen $table_screen): array;

}