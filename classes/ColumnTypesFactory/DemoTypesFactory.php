<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\Column\Post\ExcerptFactory;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class DemoTypesFactory implements ColumnTypesFactory
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): array
    {
        if ( ! $table_screen instanceof AC\PostType) {
            return [];
        }

        $factories = [];

        if (post_type_supports('excerpt', $table_screen->get_post_type())) {
            $factories['column-excerpt'] = new ExcerptFactory($this->container);
        }

        $factories['column-meta'] = new AC\Column\CustomFieldFactory(
            new AC\MetaType(AC\MetaType::POST),
            $this->container
        );

        return $factories;
    }

}