<?php

declare(strict_types=1);

namespace AC\ListScreen;

use AC;
use AC\ColumnRepository;

interface ManageValue
{

    public function manage_value(ColumnRepository $column_repository): AC\Table\ManageValue;

}