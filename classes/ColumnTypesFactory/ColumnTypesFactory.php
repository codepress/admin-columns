<?php

namespace AC\ColumnTypesFactory;

use AC\Column\ColumnFactory;
use AC\TableScreen;

interface ColumnTypesFactory
{

    /**
     * @return ColumnFactory[]
     */
    public function create(TableScreen $table_screen):array;

}