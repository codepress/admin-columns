<?php

namespace AC\Table;

use AC\Column;
use AC\Registerable;
use AC\TableScreen;

interface ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool;

    public function create(TableScreen $table_screen, Column $column): Registerable;

}