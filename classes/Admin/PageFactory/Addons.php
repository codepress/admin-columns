<?php

namespace AC\Admin\PageFactory;

use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\AdminColumns;
use AC\Integration\IntegrationRepository;

class Addons implements PageFactoryInterface
{

    protected AdminColumns $plugin;

    protected IntegrationRepository $integrations;

    protected MenuFactoryInterface $menu_factory;

    public function __construct(
        AdminColumns $plugin,
        IntegrationRepository $integrations,
        MenuFactoryInterface $menu_factory
    ) {
        $this->plugin = $plugin;
        $this->integrations = $integrations;
        $this->menu_factory = $menu_factory;
    }

    public function create(): Page\Addons
    {
        return new Page\Addons(
            $this->plugin,
            $this->integrations,
            new Admin\View\Menu($this->menu_factory->create('addons'))
        );
    }

}