<?php

namespace AC\Table;

use AC\Registerable;
use AC\TableScreen;

/**
 * Any class that wants to render specific content within the table grid
 * needs to define the render method.
 */
interface SaveHeadingFactory
{

    public function can_create(TableScreen $table_screen): bool;

    public function create(TableScreen $table_screen): ?Registerable;

}