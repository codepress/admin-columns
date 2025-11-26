<?php

namespace AC;

// TODO Tobias
interface ListTable extends CellRenderer
{

    public function render_row($id): string;

}