<?php

namespace AC\TableScreen\ManageValue;

use AC\Type\ColumnId;

/**
 * Any class that wants to render specific content within the table grid
 * needs to define the render method.
 */
interface GridRenderable
{

    public function render(ColumnId $column_id, $row_id): ?string;

}