<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;
use AC\Type\ColumnFactoryDefinition;

final class ThirdPartyFactory extends BaseFactory
{

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();

        /**
         * @deprecated 7.0.10
         */
        $factory_classes = apply_filters_deprecated(
            'ac/column/types/pro',
            [
                [],
                $table_screen,
            ],
            '7.0.9',
            'ac/column/types'
        );

        $factory_classes = apply_filters('ac/column/types', $factory_classes, $table_screen, $this->container);

        foreach ($factory_classes as $factory => $props) {
            if (is_numeric($factory)) {
                $factory = $props;
                $props = [];
            }

            $collection->add(
                new ColumnFactoryDefinition(
                    $factory, $props
                )
            );
        }

        return $collection;
    }

}