<?php

namespace AC\Admin;

use AC;

interface MenuFactoryInterface
{

    public function create(string $current): Menu;

}