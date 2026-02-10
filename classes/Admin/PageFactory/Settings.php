<?php

namespace AC\Admin\PageFactory;

use AC\Admin\Asset;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\View;
use AC\AdminColumns;

class Settings implements PageFactoryInterface
{

    protected AdminColumns $plugin;

    protected MenuFactoryInterface $menu_factory;

    protected View\MenuFactory $view_menu_factory;

    public function __construct(
        AdminColumns $plugin,
        MenuFactoryInterface $menu_factory,
        View\MenuFactory $view_menu_factory
    ) {
        $this->plugin = $plugin;
        $this->menu_factory = $menu_factory;
        $this->view_menu_factory = $view_menu_factory;
    }

    public function create(): Page\Settings
    {
        return new Page\Settings(
            $this->view_menu_factory->create($this->menu_factory, 'settings'),
            new Asset\Script\SettingsFactory($this->plugin)
        );
    }

}