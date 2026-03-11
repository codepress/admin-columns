<?php

namespace AC\ListTable;

use AC\ListTable;
use WP_Comments_List_Table;

class Comment implements ListTable
{

    private WP_Comments_List_Table $table;

    public function __construct(WP_Comments_List_Table $table)
    {
        $this->table = $table;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        $comment = get_comment($row_id);

        if ( ! $comment) {
            return '';
        }

        ob_start();

        $method = 'column_' . $column_id;

        if (method_exists($this->table, $method)) {
            call_user_func([$this->table, $method], $comment);
        } else {
            $this->table->column_default($comment, $column_id);
        }

        return ob_get_clean();
    }

    public function render_row($id): string
    {
        $comment = get_comment($id);

        if ( ! $comment) {
            return '';
        }

        ob_start();

        $this->table->single_row($comment);

        return ob_get_clean();
    }

}