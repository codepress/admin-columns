<?php

namespace AC\Table;

use AC\Registerable;
use AC\TableScreen;

interface ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool;

    public function create(GridRenderable $renderable, TableScreen $table_screen): Registerable;

}