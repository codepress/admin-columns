<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_Users_List_Table;

class User implements ListTable
{

    use WpListTableTrait;

    public function __construct(WP_Users_List_Table $table)
    {
        $this->table = $table;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        return (string)apply_filters('manage_users_custom_column', '', $column_id, $row_id);
    }

    public function render_row($id): string
    {
        return $this->table->single_row(get_userdata($id));
    }

}