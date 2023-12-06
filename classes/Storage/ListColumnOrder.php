<?php

namespace AC\Storage;

use AC\ColumnCollection;
use AC\ColumnRepository\Sort\ColumnNames;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Type\ListScreenId;
use LogicException;

class ListColumnOrder
{

    private $list_screen_repository;

    public function __construct(Storage $list_screen_repository)
    {
        $this->list_screen_repository = $list_screen_repository;
    }

    public function save(ListScreenId $list_id, array $column_names): void
    {
        $list_screen = $this->list_screen_repository->find($list_id);

        if ( ! $list_screen) {
            return;
        }

        // TODO test
        $list_screen->set_columns(
            $this->create_sorted_columns($list_screen, $column_names)
        );

        $this->list_screen_repository->save($list_screen);
    }

    private function create_sorted_columns(ListScreen $list_screen, array $names): ColumnCollection
    {
        // The Sort strategy forces a ColumnCollection
        $columns = $list_screen->get_columns(null, new ColumnNames($names));

        if ( ! $columns instanceof ColumnCollection) {
            throw new LogicException('Something went wrong.');
        }

        return $columns;
    }

}