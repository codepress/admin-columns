<?php

namespace AC\ColumnRepository\Sort;

use AC\Storage;
use AC\Type\ListScreenId;

class ManualOrder extends ColumnNames
{

    public function __construct(ListScreenId $list_id)
    {
        $storage = new Storage\UserColumnOrder();
        $column_names = $storage->exists($list_id)
            ? $storage->get($list_id)
            : [];

        parent::__construct($column_names);
    }

}