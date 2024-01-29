<?php

declare(strict_types=1);

namespace AC;

use AC\Setting\Config;

interface ColumnTypesFactory
{

    public function create(TableScreen $table_screen, Config $config = null): ?ColumnTypeCollection;

}