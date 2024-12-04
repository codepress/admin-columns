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
        $global_post = get_post();
        $post = get_post((int)$row_id);
        setup_postdata($post);
        $GLOBALS['post'] = $post;

        ob_start();

        if (method_exists($this->table, 'column_' . $column_id)) {
            call_user_func([$this->table, 'column_' . $column_id], $post);
        } else {
            $this->table->column_default($post, $column_id);
        }

        $GLOBALS['post'] = $global_post;

        return ob_get_clean();
    }

    public function render_row($id): string
    {
        $post = get_post($id);

        // Title for some columns can only be retrieved when post is set globally
        if ( ! isset($GLOBALS['post'])) {
            $GLOBALS['post'] = $post;
        }

        ob_start();

        $this->table->single_row($post);

        return ob_get_clean();
    }

}