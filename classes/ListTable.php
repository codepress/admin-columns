<?php

namespace AC;

// TODO rename to RowRenderable.
// TODO can we remove the CellRenderer interface? It is currently used by ACP\Editing\RequestHandler\InlineSave.
interface ListTable extends CellRenderer
{

    // TODO render_row is only called on the list table page.
    // TODO It should be called on this page only where the global $wp_list_table is set.
    public function render_row($id): string;

}