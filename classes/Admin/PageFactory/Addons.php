<?php

namespace AC\Admin\PageFactory;

use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Asset\Location;
use AC\IntegrationRepository;

class Addons implements PageFactoryInterface
{

    protected $location;

    protected $integrations;

    protected $menu_factory;

    public function __construct(
        Location\Absolute $location,
        IntegrationRepository $integrations,
        MenuFactoryInterface $menu_factory
    ) {
        $this->location = $location;
        $this->integrations = $integrations;
        $this->menu_factory = $menu_factory;
    }

    public function create()
    {
        return new Page\Addons(
            $this->location,
            $this->integrations,
            new Admin\View\Menu($this->menu_factory->create('addons'))
        );
    }

}