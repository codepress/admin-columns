<?php

namespace AC\TableScreen;

use AC\CellRenderer;
use AC\Registerable;
use AC\TableScreen;

interface ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool;

    public function create(TableScreen $table_screen, CellRenderer $renderable, int $priority = 100): Registerable;

}