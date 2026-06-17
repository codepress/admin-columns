<?php

declare(strict_types=1);

namespace AC\ListTable;

use AC\ListTable;
use WP_Comments_List_Table;

class Comment implements ListTable
{
    use RenderColumnTrait;

    private WP_Comments_List_Table $table;

    public function __construct(WP_Comments_List_Table $table)
    {
        $this->table = $table;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        $comment = get_comment($row_id);

        if (! $comment) {
            return '';
        }

        return $this->render_column($this->table, $column_id, $comment);
    }

    public function render_row($id): string
    {
        $comment = get_comment($id);

        if (! $comment) {
            return '';
        }

        ob_start();

        $this->table->single_row($comment);

        return (string)ob_get_clean();
    }

}
