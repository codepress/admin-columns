<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC\TableScreen;

interface TableRowsFactory
{

    public function create(TableScreen $table_screen): ?TableRows;

}