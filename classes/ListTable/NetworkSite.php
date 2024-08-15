<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_MS_Sites_List_Table;

class NetworkSite implements ListTable
{

    use WpListTableTrait;

    public function __construct(WP_MS_Sites_List_Table $table)
    {
        $this->table = $table;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        ob_start();

        $method = 'column_' . $column_id;

        $blog = get_site($row_id);

        if ( ! $blog) {
            return '';
        }

        if (method_exists($this->table, $method)) {
            call_user_func([$this->table, $method], $blog->to_array());
        } else {
            $this->table->column_default($blog->to_array(), $column_id);
        }

        return ob_get_clean();
    }

    public function render_row($id): string
    {
        ob_start();
        $this->table->single_row(get_site($id));

        return ob_get_clean();
    }

}