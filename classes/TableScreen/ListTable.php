<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;

interface ListTable
{

    // TODO should we remove this from the TableScreen?
    public function list_table(): AC\ListTable;

}