<?php

namespace AC\Controller\ColumnRequest;

use AC\Column;
use AC\Controller\ColumnRequest;
use AC\ListScreen;
use AC\Request;

class Select extends ColumnRequest
{

    protected function get_column(Request $request, ListScreen $list_screen): ?Column
    {
        return $list_screen->get_column_by_type($request->get('type'));
    }

}