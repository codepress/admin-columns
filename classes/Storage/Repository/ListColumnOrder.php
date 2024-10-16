<?php

namespace AC\Storage\Repository;

use AC\ColumnCollection;
use AC\ColumnIterator;
use AC\ColumnRepository\Sort\ColumnNames;
use AC\ListScreenRepository\Storage;
use AC\Type\ListScreenId;

class ListColumnOrder
{

    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function save(ListScreenId $list_id, array $column_names): void
    {
        $list_screen = $this->storage->find($list_id);

        if ( ! $list_screen || $list_screen->is_read_only()) {
            return;
        }

        $list_screen->set_columns(
            $this->modify_columns($list_screen->get_columns(), $column_names)
        );

        $this->storage->save($list_screen);
    }

    private function modify_columns(ColumnIterator $columns, array $names): ColumnCollection
    {
        return (new ColumnNames($names))->sort($columns);
    }

}