<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_Media_List_Table;

class Media implements ListTable
{

    private WP_Media_List_Table $table;

    public function __construct(WP_Media_List_Table $table)
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
        // Author column depends on this global to be set.
        global $authordata;

        // Title for some columns can only be retrieved when post is set globally
        if ( ! isset($GLOBALS['post'])) {
            $GLOBALS['post'] = get_post($id);
        }

        $authordata = get_userdata(get_post_field('post_author', $id));

        ob_start();

        $this->table->single_row(get_post($id));

        return ob_get_clean();
    }

}