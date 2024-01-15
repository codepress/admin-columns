<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use AC\Type\ListKey;

class Site implements Filter
{

    public function filter(ListScreenCollection $list_screens): ListScreenCollection
    {
        $collection = new ListScreenCollection();

        foreach ($list_screens as $list_screen) {
            if ($this->is_site($list_screen->get_key())) {
                $collection->add($list_screen);
            }
        }

        return $collection;
    }

    protected function is_site(ListKey $list_key): bool
    {
        return ! in_array((string)$list_key, Network::KEYS, true);
    }

}