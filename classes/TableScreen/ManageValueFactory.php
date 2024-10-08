<?php

namespace AC\TableScreen;

use AC\Registerable;
use AC\TableScreen;
use AC\TableScreen\ManageValue\GridRenderable;

interface ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool;

    public function create(TableScreen $table_screen, GridRenderable $renderable, int $priority = 100): Registerable;

}