<?php

namespace AC;

interface ListTable
{

    // TODO render_row is only called on the list table page.
    // TODO It should be called on this page only where the global $wp_list_table is set.
    public function render_row($id): string;

    public function render_cell(string $column_id, $row_id): ?string;

}