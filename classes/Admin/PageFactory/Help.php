<?php

namespace AC\Admin\PageFactory;

use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Asset\Location;
use AC\Deprecated\HookCollectionFactory;
use AC\Deprecated\Hooks;

class Help implements PageFactoryInterface
{

    protected Location\Absolute $location;

    protected MenuFactoryInterface $menu_factory;

    public function __construct(Location\Absolute $location, MenuFactoryInterface $menu_factory)
    {
        $this->location = $location;
        $this->menu_factory = $menu_factory;
    }

    public function create(): Page\Help
    {
        return new Page\Help(
            new Hooks(new HookCollectionFactory()),
            $this->location,
            new Admin\View\Menu($this->menu_factory->create('help'))
        );
    }

}