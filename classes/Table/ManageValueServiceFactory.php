<?php

namespace AC\Table;

use AC\ListScreen;
use AC\Registerable;
use AC\TableScreen;

/**
 * Any class that wants to render specific content within the table grid
 * needs to define the render method.
 */
interface ManageValueServiceFactory
{

    public function create(TableScreen $table_screen, ListScreen $list_screen): ?Registerable;

}