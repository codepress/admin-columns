<?php

declare(strict_types=1);

namespace AC\Admin\View;

use AC\Admin\MenuFactoryInterface;
use AC\AdminColumns;

final class MenuFactory
{

    private AdminColumns $plugin;

    public function __construct(AdminColumns $plugin)
    {
        $this->plugin = $plugin;
    }

    public function create(MenuFactoryInterface $menu_factory, string $current): Menu
    {
        return new Menu($this->plugin->get_location(), $menu_factory->create($current));
    }

}