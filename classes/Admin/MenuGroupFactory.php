<?php

namespace AC\Admin;

use AC\Admin\Type\MenuGroup;
use AC\TableScreen;

interface MenuGroupFactory
{

    public function create(TableScreen $table_screen): ?MenuGroup;

}