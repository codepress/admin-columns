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

    public function get_column_value(string $column, int $id): string
    {
        return (string)apply_filters('manage_users_custom_column', '', $column, $id);
    }

    public function render_row(int $id): string
    {
        return $this->table->single_row(get_userdata($id));
    }

}