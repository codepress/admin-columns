<?php

declare(strict_types=1);

namespace AC\ListTable;

use AC\ListTable;
use WP_Media_List_Table;

class Media implements ListTable
{
    use RenderColumnTrait;

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

        if (! $post) {
            return '';
        }

        setup_postdata($post);
        $GLOBALS['post'] = $post;

        $output = $this->render_column($this->table, $column_id, $post);

        $GLOBALS['post'] = $global_post;

        return $output;
    }

    public function render_row($id): string
    {
        // Author column depends on this global to be set.
        global $authordata;

        $post = get_post($id);

        if (! $post) {
            return '';
        }

        // Title for some columns can only be retrieved when post is set globally
        if (! isset($GLOBALS['post'])) {
            $GLOBALS['post'] = $post;
        }

        $authordata = get_userdata((int)$post->post_author) ?: null;

        ob_start();

        $this->table->single_row($post);

        return (string)ob_get_clean();
    }

}
