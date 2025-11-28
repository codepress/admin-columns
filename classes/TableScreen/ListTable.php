<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;

// TODO remove this interface and use ListTable directly instead
interface ListTable
{

    public function list_table(): AC\ListTable;

}