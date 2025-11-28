<?php

namespace AC\TableScreen;

use AC\Table\ManageValue\ValueFormatter;
use AC\TableScreen;

interface ManageValueServiceFactory
{

    public function can_create(TableScreen $table_screen): bool;

    public function create(
        TableScreen $table_screen,
        ValueFormatter $formatter,
        int $priority = 100
    ): ManageValueService;

}