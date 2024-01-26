<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListScreen;

interface ManageValue
{

    public function manage_value(ListScreen $list_screen): AC\Table\ManageValue;

}