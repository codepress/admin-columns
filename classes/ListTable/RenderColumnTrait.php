<?php

declare(strict_types=1);

namespace AC\ListTable;

use WP_List_Table;

trait RenderColumnTrait
{
    private function render_column(WP_List_Table $table, string $column_id, $item): string
    {
        $method = 'column_' . $column_id;

        ob_start();

        if (method_exists($table, $method)) {
            $table->$method($item);
        } else {
            $table->column_default($item, $column_id);
        }

        return (string)ob_get_clean();
    }
}