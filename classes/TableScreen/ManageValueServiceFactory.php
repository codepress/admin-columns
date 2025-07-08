<?php

namespace AC\TableScreen;

use AC\CellRenderer;
use AC\TableScreen;

interface ManageValueServiceFactory
{

    public function can_create(TableScreen $table_screen): bool;

    public function create(
        TableScreen $table_screen,
        CellRenderer $renderable,
        int $priority = 100
    ): ManageValueService;

}