<?php

namespace AC;

interface ListTable
{

    public function get_column_value(string $column, $id): string;

    public function get_total_items(): int;

    public function render_row($id): string;

}