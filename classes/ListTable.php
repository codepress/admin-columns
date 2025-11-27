<?php

namespace AC;

// TODO rename to RowRenderable.
// TODO can we remove the CellRenderer interface? It is currently used by ACP\Editing\RequestHandler\InlineSave.
interface ListTable extends CellRenderer
{

    public function render_row($id): string;

}