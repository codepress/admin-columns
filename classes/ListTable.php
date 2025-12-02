<?php

namespace AC;

interface ListTable
{

    public function render_row($id): string;

    public function render_cell(string $column_id, $row_id): ?string;

}