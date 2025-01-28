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

    private bool $is_acp_active;

    public function __construct(
        AC\AdminColumns $plugin,
        MenuFactoryInterface $menu_factory,
        bool $is_acp_active
    ) {
        $this->plugin = $plugin;
        $this->menu_factory = $menu_factory;
        $this->is_acp_active = $is_acp_active;
    }

    public function create()
    {
        return new Page\Settings(
            new AC\Admin\View\Menu($this->menu_factory->create('settings')),
            new AC\Admin\Asset\Script\SettingsFactory($this->plugin)
        );

        // TODO show this?
        //        if ( ! $this->is_acp_active) {
        //        $page->add_section(new Section\ProCta(), 50);
        //        }

    }

}