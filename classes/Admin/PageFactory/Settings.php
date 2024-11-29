<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Asset\Location;

class Settings implements PageFactoryInterface
{

    protected Location\Absolute $location;

    protected MenuFactoryInterface $menu_factory;

    private bool $is_acp_active;

    public function __construct(
        Location\Absolute $location,
        MenuFactoryInterface $menu_factory,
        bool $is_acp_active
    ) {
        $this->location = $location;
        $this->menu_factory = $menu_factory;
        $this->is_acp_active = $is_acp_active;
    }

    public function create()
    {
        return new Page\Settings(
            new AC\Admin\View\Menu($this->menu_factory->create('settings')),
            new AC\Admin\Asset\Script\SettingsFactory($this->location)
        );

        // TODO show this?
        //        if ( ! $this->is_acp_active) {
        //            $page->add_section(new Section\ProCta(), 50);
        //        }

    }

}