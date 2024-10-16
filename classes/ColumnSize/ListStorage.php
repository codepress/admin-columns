<?php

namespace AC\ColumnSize;

use AC;
use AC\Column;
use AC\Column\ColumnFactory;
use AC\ColumnCollection;
use AC\ColumnFactories\Aggregate;
use AC\ColumnIterator;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Setting\Config;
use AC\Type\ColumnId;
use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;
use InvalidArgumentException;

class ListStorage
{

    private Storage $storage;

    private Aggregate $column_factory;

    private AC\Setting\ConfigFactory $config_factory;

    public function __construct(Storage $storage, AC\Setting\ConfigFactory $config_factory, Aggregate $column_factory)
    {
        $this->storage = $storage;
        $this->column_factory = $column_factory;
        $this->config_factory = $config_factory;
    }

    private function get_column_factory($table_screen, $column_type): ?ColumnFactory
    {
        foreach ($this->column_factory->create($table_screen) as $factory) {
            if ($factory->get_column_type() === $column_type) {
                return $factory;
            }
        }

        return null;
    }

    public function save(ListScreenId $list_id, ColumnId $column_id, ColumnWidth $column_width): void
    {
        $list_screen = $this->storage->find($list_id);

        if ( ! $list_screen) {
            return;
        }

        $column = $list_screen->get_column($column_id);

        if ( ! $column) {
            return;
        }

        $factory = $this->get_column_factory($list_screen->get_table_screen(), $column->get_type());

        if ( ! $factory) {
            return;
        }

        $config = $this->config_factory->create($column)->all();

        $config['width'] = (string)$column_width->get_value();
        $config['width_unit'] = $column_width->get_unit();

        $column = $factory->create(new Config($config));
        $columns = $this->modify_collection($list_screen->get_columns(), $column);

        $list_screen->set_columns($columns);

        $this->storage->save($list_screen);
    }

    private function modify_collection(ColumnIterator $collection, Column $column): ColumnCollection
    {
        $columns = iterator_to_array($collection);

        foreach ($columns as $k => $_column) {
            if ($_column->get_id()->equals($column->get_id())) {
                $columns[$k] = $column;
            }
        }

        return new ColumnCollection($columns);
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
            $width = $this->create($column);

            if ($width) {
                $results[(string)$column->get_id()] = $width;
            }
        }

        return $results;
    }

    private function create(Column $column): ?ColumnWidth
    {
        $width = (int)$column->get_setting('width')->get_input()->get_value();

        if ($width < 1) {
            return null;
        }

        $unit = (string)$column->get_setting('width_unit')->get_input()->get_value();

        try {
            $width = new ColumnWidth($unit, $width);
        } catch (InvalidArgumentException $e) {
            return null;
        }

        return $width;
    }

    public function get(ListScreen $list_screen, ColumnId $column_id): ?ColumnWidth
    {
        $column = $list_screen->get_column($column_id);

        if ( ! $column) {
            return null;
        }

        return $this->create($column);
    }

}