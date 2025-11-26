<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_MS_Users_List_Table;

class NetworkUser implements ListTable
{

    public function __construct(WP_MS_Users_List_Table $table)
    {
        $this->table = $table;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        ob_start();

        $method = 'column_' . $column_id;

        if (method_exists($this->table, $method)) {
            call_user_func([$this->table, $method], get_userdata($row_id));
        } else {
            $this->table->column_default(get_userdata($row_id), $column_id);
        }

        return ob_get_clean();
    }

    public function render_row($id): string
    {
        ob_start();
        $this->table->single_row(get_userdata($id));

        return ob_get_clean();
    }

}