<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use AC\Type;

final class ListScreenStatus implements Filter
{

    private Type\ListScreenStatus $status;

    public function __construct(Type\ListScreenStatus $status)
    {
        $this->status = $status;
    }

    public function filter(ListScreenCollection $list_screens): ListScreenCollection
    {
        $collection = new ListScreenCollection();

        foreach ($list_screens as $list_screen) {
            if ($this->status->equals($list_screen->get_status())) {
                $collection->add($list_screen);
            }
        }

        return $collection;
    }

}