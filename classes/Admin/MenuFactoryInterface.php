<?php

namespace AC\Admin;

interface MenuFactoryInterface
{

    public function create(string $current): Menu;

}