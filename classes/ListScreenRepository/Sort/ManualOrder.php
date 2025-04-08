<?php

namespace AC\ListScreenRepository\Sort;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Sort;
use AC\Storage;

class ManualOrder implements Sort
{

    private Storage\Repository\ListScreenOrder $list_screen_order;

    public function __construct()
    {
        $this->list_screen_order = new Storage\Repository\ListScreenOrder();
    }

    public function sort(ListScreenCollection $list_screens): ListScreenCollection
    {
        if ( ! $list_screens->count()) {
            return $list_screens;
        }

        $key = $list_screens->get_first()->get_table_id();

        $layouts = [];

        foreach ($list_screens as $list_screen) {
            $layouts[(string)$list_screen->get_id()] = $list_screen;
        }

        $ordered = new ListScreenCollection();

        foreach ($this->list_screen_order->get($key) as $layout_id) {
            if ( ! isset($layouts[$layout_id])) {
                continue;
            }

            $ordered->add($layouts[$layout_id]);

            unset($layouts[$layout_id]);
        }

        foreach ($layouts as $list_screen) {
            $ordered->add($list_screen);
        }

        return $ordered;
    }

}