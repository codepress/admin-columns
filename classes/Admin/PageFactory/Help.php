<?php

namespace AC\Admin\PageFactory;

use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Deprecated\HookCollectionFactory;
use AC\Deprecated\Hooks;

class Help implements PageFactoryInterface
{

    protected MenuFactoryInterface $menu_factory;

    public function __construct(MenuFactoryInterface $menu_factory)
    {
        $this->menu_factory = $menu_factory;
    }

    public function create(): Page\Help
    {
        return new Page\Help(
            new Hooks(new HookCollectionFactory()),
            new Admin\View\Menu($this->menu_factory->create('help'))
        );
    }

}