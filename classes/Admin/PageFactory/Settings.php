<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\Section;
use AC\Asset\Location;

class Settings implements PageFactoryInterface
{

    protected $location;

    protected $menu_factory;

    private $is_acp_active;

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
        $page = new Page\Settings(
            new AC\Admin\View\Menu($this->menu_factory->create('settings')),
            $this->location
        );

        $page->add_section(new Section\General([new Section\Partial\ShowEditButton()]))
             ->add_section(new Section\Restore(), 40);

        if ( ! $this->is_acp_active) {
            $page->add_section(new Section\ProCta(), 50);
        }

        return $page;
    }

}