<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;

class Settings implements PageFactoryInterface
{

    protected AC\AdminColumns $plugin;

    protected MenuFactoryInterface $menu_factory;

    public function __construct(
        AC\AdminColumns $plugin,
        MenuFactoryInterface $menu_factory
    ) {
        $this->plugin = $plugin;
        $this->menu_factory = $menu_factory;
    }

    public function create(): Page\Settings
    {
        return new Page\Settings(
            new AC\Admin\View\Menu($this->menu_factory->create('settings')),
            new AC\Admin\Asset\Script\SettingsFactory($this->plugin)
        );
    }

}