<?php

declare(strict_types=1);

namespace AC\ListTable;

use AC\ListTable;
use WP_MS_Users_List_Table;

class NetworkUser implements ListTable
{
    use RenderColumnTrait;

    private WP_MS_Users_List_Table $table;

    public function __construct(WP_MS_Users_List_Table $table)
    {
        $this->table = $table;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        $user = get_userdata($row_id);

        if (! $user) {
            return '';
        }

        return $this->render_column($this->table, $column_id, $user);
    }

    public function render_row($id): string
    {
        $user = get_userdata($id);

        if (! $user) {
            return '';
        }

        ob_start();

        $this->table->single_row($user);

        return (string)ob_get_clean();
    }

}
