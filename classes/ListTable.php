<?php

namespace AC;

interface ListTable extends CellRenderer
{

    public function render_row($id): string;

    // TODO remove usages
    public function get_total_items(): int;

}