<?php

namespace AC\Admin\View;

use AC\Admin;
use AC\Asset\Location;
use AC\View;

class Menu extends View
{

    public function __construct(Location $location, Admin\Menu $menu)
    {
        $items = $menu->get_items();
        $this->sort_by_position($items);

        parent::__construct(['menu_items' => $items]);

        $this
            ->set_template('admin/menu')
            ->set('url', $location->get_url());
    }

    private function sort_by_position(array &$items): void
    {
        usort($items, static function (Admin\Type\MenuItem $a, Admin\Type\MenuItem $b) {
            return $a->get_position() <=> $b->get_position();
        });
    }

}