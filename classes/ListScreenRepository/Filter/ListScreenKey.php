<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use AC\Type\ListKey;

final class ListScreenKey implements Filter
{

    private $key;

    public function __construct(ListKey $key)
    {
        $this->key = $key;
    }

    public function filter(ListScreenCollection $list_screens): ListScreenCollection
    {
        $collection = new ListScreenCollection();

        foreach ($list_screens as $list_screen) {
            if ($this->key->equals($list_screen->get_key())) {
                $collection->add($list_screen);
            }
        }

        return $collection;
    }

}