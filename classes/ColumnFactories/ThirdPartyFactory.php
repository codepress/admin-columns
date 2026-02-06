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

        $factory_classes = apply_filters('ac/column/types', [], $table_screen, $this->container);

        /**
         * @deprecated 7.0.10
         */
        $factory_classes = apply_filters_deprecated(
            'ac/column/types/pro',
            [
                $factory_classes,
                $table_screen,
            ],
            '7.0.10',
            'ac/column/types'
        );

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