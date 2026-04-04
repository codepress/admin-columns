<?php

declare(strict_types=1);

namespace AC\Table;

use AC\ListScreen;

class PrimaryColumnFactory
{

    public function create(ListScreen $list_screen): PrimaryColumn
    {
        return new PrimaryColumn($list_screen);
    }

}