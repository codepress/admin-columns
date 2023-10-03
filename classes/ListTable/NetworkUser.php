<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_MS_Users_List_Table;

class NetworkUser implements ListTable
{

    use WpListTableTrait;

    public function __construct(WP_MS_Users_List_Table $table)
    {
        $this->table = $table;
    }

    public function get_column_value(string $column, $id): string
    {
        ob_start();

        $method = 'column_' . $column;

        if (method_exists($this->table, $method)) {
            call_user_func([$this->table, $method], get_userdata($id));
        } else {
            $this->table->column_default(get_userdata($id), $column);
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