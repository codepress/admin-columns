<?php

namespace AC\ColumnSize;

use AC;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;

class ListStorage
{

    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function save(ListScreenId $list_id, string $column_name, ColumnWidth $column_width): void
    {
        $list_screen = $this->storage->find($list_id);

        if ( ! $list_screen) {
            return;
        }

        $column = $list_screen->get_column($column_name);

        if ( ! $column) {
            return;
        }

        $column->set_option('width', (string)$column_width->get_value());
        $column->set_option('width_unit', $column_width->get_unit());

        $this->storage->save($list_screen);
    }

    /**
     * @param ListScreen $list_screen
     *
     * @return ColumnWidth[]
     */
    public function get_all(ListScreen $list_screen): array
    {
        $results = [];

        foreach ($list_screen->get_columns() as $column) {
            $name = $column->get_name();

            $results[$name] = $this->get($list_screen, $name);
        }

        return array_filter($results);
    }

    public function get(ListScreen $list_screen, string $column_name): ?ColumnWidth
    {
        $column = $list_screen->get_column($column_name);

        if ( ! $column) {
            return null;
        }

        $setting = $column->get_setting('width');

        return $setting instanceof AC\Settings\Column\Width
            ? $setting->get_column_width()
            : null;
    }

}