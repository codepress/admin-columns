<?php

namespace AC\TableScreen;

use AC\Table\ManageValue\RenderFactory;
use AC\TableScreen;

interface ManageValueServiceFactory
{

    public function can_create(TableScreen $table_screen): bool;

    public function create(
        TableScreen $table_screen,
        RenderFactory $factory,
        int $priority = 100
    ): ManageValueService;

}