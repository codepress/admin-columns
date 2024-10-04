<?php

declare(strict_types=1);

namespace AC\Table\GridRenderable;

use AC\ListScreen;
use AC\Table\GridRenderable;
use AC\Table\Renderable;

class ColumnRenderable implements GridRenderable
{

    private ListScreen $list_screen;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
    }

    public function render($column_id, $row_id): ?string
    {
        $column = $this->list_screen->get_column($column_id);

        if ( ! $column) {
            return null;
        }

        return (new Renderable\ColumnRenderable($column->get_formatters()))->render($row_id);
    }

}