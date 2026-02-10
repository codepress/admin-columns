<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use AC\Type;

final class ListScreenId implements Filter
{

    private Type\ListScreenId $id;

    public function __construct(Type\ListScreenId $id)
    {
        $this->id = $id;
    }

    public function filter(ListScreenCollection $list_screens): ListScreenCollection
    {
        $filtered = new ListScreenCollection();

        foreach ($list_screens as $list_screen) {
            if ($list_screen->get_id()->equals($this->id)) {
                $filtered->add($list_screen);
            }
        }

        return $filtered;
    }

}