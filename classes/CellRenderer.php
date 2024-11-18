<?php

namespace AC;

interface CellRenderer
{

    public function render_cell(string $column_id, $row_id):? string;

}