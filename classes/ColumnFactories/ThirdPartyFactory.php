<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;

final class ThirdPartyFactory extends BaseFactory
{

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();

        do_action('ac/v2/column_types', $collection, $table_screen);

        return $collection;
    }

}