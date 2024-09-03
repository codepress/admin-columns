<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\ColumnFactories;
use AC\TableScreen;
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
        $context = TableScreenContext::from_table_screen($table_screen);

        $defaults = [
            'list_key'  => $table_screen->get_key(),
            'meta_type' => $context->get_meta_type(),
            'context'   => $context,
            'post_type' => $context->has_post_type() ? $context->get_post_type() : null,
            'taxonomy'  => $context->has_taxonomy() ? $context->get_taxonomy() : null,
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