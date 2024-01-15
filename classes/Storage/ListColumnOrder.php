<?php

namespace AC\Storage;

use AC\ColumnCollection;
use AC\ColumnRepository\Sort\ColumnNames;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Type\ListScreenId;

class ListColumnOrder
{

    private $storage;

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
            $this->create_sorted_columns($list_screen, $column_names)
        );

        $this->storage->save($list_screen);
    }

    private function create_sorted_columns(ListScreen $list_screen, array $names): ColumnCollection
    {
        return new ColumnCollection(iterator_to_array($list_screen->get_columns(null, new ColumnNames($names))));
    }

}