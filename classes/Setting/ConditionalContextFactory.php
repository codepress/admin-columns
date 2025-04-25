<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Column;
use AC\TableScreen;

interface ConditionalContextFactory extends ContextFactory
{

    public function supports(Column $column, TableScreen $table_screen): bool;

}