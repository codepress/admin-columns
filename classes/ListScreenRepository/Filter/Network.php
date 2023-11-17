<?php

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;

class Network implements Filter
{

    public const KEYS = [
        'wp-ms_sites',
        'wp-ms_users',
    ];

    public function filter(ListScreenCollection $list_screens): ListScreenCollection
    {
        $collection = new ListScreenCollection();

        foreach ($list_screens as $list_screen) {
            if (in_array((string)$list_screen->get_key(), self::KEYS, true)) {
                $collection->add($list_screen);
            }
        }

        return $collection;
    }

}