<?php

declare(strict_types=1);

namespace AC\ListScreen;

use AC;

// TODO check instanceof uses. This should only be used with a TableScreen
interface ListTable
{

    public function list_table(): AC\ListTable;

}