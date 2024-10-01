<?php

namespace AC\Table;

use AC\Registerable;
use AC\TableScreen;
use AC\Type\ColumnId;

interface ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool;

    public function create(ColumnId $column_id, Renderable $renderable, TableScreen $table_screen): Registerable;

}