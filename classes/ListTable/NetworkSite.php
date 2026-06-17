<?php

declare(strict_types=1);

namespace AC\ListTable;

use AC\ListTable;
use WP_MS_Sites_List_Table;

class NetworkSite implements ListTable
{
    use RenderColumnTrait;

    private WP_MS_Sites_List_Table $table;

    public function __construct(WP_MS_Sites_List_Table $table)
    {
        $this->table = $table;
    }

    public function render_cell(string $column_id, $row_id): string
    {
        $blog = get_site($row_id);

        if (! $blog) {
            return '';
        }

        return $this->render_column($this->table, $column_id, $blog->to_array());
    }

    public function render_row($id): string
    {
        $site = get_site($id);

        if (! $site) {
            return '';
        }

        ob_start();

        $this->table->single_row($site);

        return (string)ob_get_clean();
    }

}
