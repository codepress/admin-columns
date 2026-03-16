<?php

namespace AC\Admin\PageFactory;

use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\View;
use AC\AdminColumns;
use AC\Deprecated\HookCollectionFactory;
use AC\Deprecated\Hooks;

class Help implements PageFactoryInterface
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

    public function create(): Page\Help
    {
        return new Page\Help(
            new Hooks(new HookCollectionFactory()),
            $this->view_menu_factory->create($this->menu_factory, 'help')
        );
    }

}