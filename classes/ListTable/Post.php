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
        // populate globals
        $post = get_post((int)$row_id);
        $GLOBALS['post'] = $post;
        setup_postdata($post);

        ob_start();

        if (method_exists($this->table, 'column_' . $column_id)) {
            call_user_func([$this->table, 'column_' . $column_id], $post);
        } else {
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