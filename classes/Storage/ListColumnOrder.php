<?php

namespace AC\Storage;

use AC\Column;
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

        $columns = $list_screen->get_columns();

        $list_screen->set_columns(
            $this->order_columns($columns, $column_names)
        );

        $this->list_screen_repository->save($list_screen);
    }

    /**
     * @param Column[] $columns
     * @param array    $names
     *
     * @return Column[]
     */
    private function order_columns(array $columns, array $names): array
    {
        $ordered = [];
        $last = [];
        foreach ($columns as $column) {
            $key = array_search($column->get_name(), $names);

            if (false === $key) {
                $last[] = $column;
                continue;
            }

            $ordered[$key] = $column;
        }
        ksort($ordered);

        return array_merge($ordered, $last);
    }

}