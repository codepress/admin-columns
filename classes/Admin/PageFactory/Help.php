<?php

namespace AC\Admin\PageFactory;

use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Deprecated\HookCollectionFactory;
use AC\Deprecated\Hooks;
use AC\Entity\Plugin;

class Help implements PageFactoryInterface
{

    protected Plugin $plugin;

    protected MenuFactoryInterface $menu_factory;

    public function __construct(Plugin $plugin, MenuFactoryInterface $menu_factory)
    {
        $this->plugin = $plugin;
        $this->menu_factory = $menu_factory;
    }

    public function create(): Page\Help
    {
        return new Page\Help(
            new Hooks(new HookCollectionFactory()),
            $this->plugin,
            new Admin\View\Menu($this->menu_factory->create('help'))
        );
    }

}