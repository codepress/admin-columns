<?php

declare(strict_types=1);

namespace AC;

use AC\Collection\ColumnFactories;

interface ColumnFactoryCollectionFactory
{

    public function create(TableScreen $table_screen): ColumnFactories;

}