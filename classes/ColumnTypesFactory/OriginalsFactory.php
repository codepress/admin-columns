<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\ColumnTypeCollection;
use AC\DefaultColumnsRepository;
use AC\TableScreen;

class OriginalsFactory implements AC\ColumnTypesFactory
{

    public function create(TableScreen $table_screen): ColumnTypeCollection
    {
        return (new DefaultColumnsRepository($table_screen->get_key()))->find_all();
    }

}