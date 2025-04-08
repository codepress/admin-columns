<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use AC\Type\TableId;

final class TableScreenId implements Filter
{

    private TableId $table_id;

    public function __construct(TableId $key)
    {
        $this->table_id = $key;
    }

    public function filter(ListScreenCollection $list_screens): ListScreenCollection
    {
        $collection = new ListScreenCollection();

        foreach ($list_screens as $list_screen) {
            if ($this->table_id->equals($list_screen->get_table_id())) {
                $collection->add($list_screen);
            }
        }

        return $collection;
    }

}