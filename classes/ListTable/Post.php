<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_Posts_List_Table;

class Post implements ListTable
{

    use WpListTableTrait;

    public function __construct(WP_Posts_List_Table $table)
    {
        $this->table = $table;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        ob_start();

        $method = 'column_' . $column_id;

        if (method_exists($this->table, $method)) {
            call_user_func([$this->table, $method], get_post($row_id));
        } else {
            $post = get_post($row_id);

            // populate globals
            setup_postdata($post);

            $this->table->column_default($post, $column_id);
        }

        return ob_get_clean();
    }

    public function render_row($id): string
    {
        ob_start();

        $this->table->single_row(get_post($id));

        return ob_get_clean();
    }

}