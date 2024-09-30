<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;
use AC\Type\ColumnFactoryDefinition;

// TODO determine if we have a hook for free as well
final class ThirdPartyFactory extends BaseFactory
{

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();
        $factory_classes = apply_filters('ac/v2/column_types', [], $table_screen);

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