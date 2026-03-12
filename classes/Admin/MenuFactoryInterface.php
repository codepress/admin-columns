<?php

declare(strict_types=1);

namespace AC\Admin;

interface MenuFactoryInterface
{

    public function create(string $current): Menu;

}