<?php

declare(strict_types=1);

namespace AC\Admin;

use AC\Admin\MenuGroupFactory\Aggregate;
use AC\Admin\Type\MenuListItem;
use AC\Table\TableScreenCollection;

class MenuListFactory
{

    private $group_factory;

    public function __construct(Aggregate $group_factory)
    {
        $this->group_factory = $group_factory;
    }

    public function create(TableScreenCollection $table_screens): MenuListItems
    {
        $items = [];

        foreach ($table_screens as $table_screen) {
            if ($table_screen->is_network()) {
                continue;
            }

            $items[] =
                new MenuListItem(
                    (string)$table_screen->get_key(),
                    (string)$table_screen->get_labels(),
                    $this->group_factory->create($table_screen)
                );
        }

        usort($items, [$this, 'sort_menu_by_label']);
        usort($items, [$this, 'sort_menu_by_group']);

        $menu = new MenuListItems($items);

        do_action('ac/admin/menu_list', $menu);

        return $menu;
    }

    public function sort_menu_by_label(MenuListItem $a, MenuListItem $b): int
    {
        return strnatcmp($a->get_label(), $b->get_label());
    }

    public function sort_menu_by_group(MenuListItem $a, MenuListItem $b): int
    {
        if ($a->get_group()->get_priority() === $b->get_group()->get_priority()) {
            return 0;
        }

        return $a->get_group()->get_priority() > $b->get_group()->get_priority() ? 1 : -1;
    }

}