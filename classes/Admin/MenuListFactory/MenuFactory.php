<?php

declare(strict_types=1);

namespace AC\Admin\MenuListFactory;

use AC\Admin\MenuListFactory;
use AC\Admin\MenuListItems;
use AC\Admin\Type\MenuListItem;
use AC\Table\TableScreensFactoryInterface;
use AC\TableScreen;

class MenuFactory implements MenuListFactory
{

    private $table_screens_factory;

    public function __construct(TableScreensFactoryInterface $factory)
    {
        $this->table_screens_factory = $factory;
    }

    private function create_menu_item(TableScreen $table_screen): MenuListItem
    {
        // TODO check all usages of filter
        $group = (string)apply_filters('ac/admin/menu_group', $table_screen->get_group(), $table_screen);

        return new MenuListItem(
            (string)$table_screen->get_key(),
            (string)$table_screen->get_labels(),
            $group ?: 'other'
        );
    }

    public function create(): MenuListItems
    {
        $menu = new MenuListItems();

        foreach ($this->table_screens_factory->create()->all() as $table_screen) {
            if ($table_screen->is_network()) {
                continue;
            }

            $menu->add($this->create_menu_item($table_screen));
        }

        do_action('ac/admin/menu_list', $menu);

        return $menu;
    }

}