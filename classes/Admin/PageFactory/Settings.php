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

    private $edit_button;

    public function __construct(
        Location\Absolute $location,
        MenuFactoryInterface $menu_factory,
        bool $is_acp_active,
        AC\Storage\EditButton $edit_button
    ) {
        $this->location = $location;
        $this->menu_factory = $menu_factory;
        $this->is_acp_active = $is_acp_active;
        $this->edit_button = $edit_button;
    }

    public function create(): Page\Settings
    {
        $page = new Page\Settings(
            new AC\Admin\View\Menu($this->menu_factory->create('settings')),
            $this->location
        );

        $page
            ->add_section(
                new Section\General([
                    new Section\Partial\ShowEditButton($this->edit_button),
                ])
            )
            ->add_section(new Section\Restore(), 40);

        if ( ! $this->is_acp_active) {
            $page->add_section(new Section\ProCta(), 50);
        }

        return $page;
    }

}