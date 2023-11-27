<?php

namespace AC\Storage;

use AC\ColumnCollection;
use AC\ColumnRepository;
use AC\ColumnRepository\Sort\ColumnNames;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Type\ListScreenId;

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

        $list_screen->set_columns(
            $this->get_columns($list_screen, $column_names)
        );

        $this->list_screen_repository->save($list_screen);
    }

    private function get_columns(ListScreen $list_screen, array $names): ColumnCollection
    {
        return (new ColumnRepository($list_screen))->find_all([
            'sort' => new ColumnNames($names),
        ]);
    }

}