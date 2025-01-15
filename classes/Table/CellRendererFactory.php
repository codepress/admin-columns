<?php

declare(strict_types=1);

namespace AC\Table;

use AC\CellRenderer;
use AC\ListScreen;

interface CellRendererFactory
{

    public function create(ListScreen $list_screen): CellRenderer;

}