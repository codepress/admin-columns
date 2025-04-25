<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Column;
use AC\TableScreen;

interface ContextFactory
{

    public function create(Column $column, TableScreen $table_screen): Context;

}