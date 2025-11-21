<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\CellRenderer;
use AC\ListScreen;

class ListScreenRenderableFactory
{

    public function create(ListScreen $list_screen): CellRenderer
    {
        return new ListScreenRenderable($list_screen);
    }

}