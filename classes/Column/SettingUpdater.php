<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\ColumnCollection;
use AC\ColumnFactories\Aggregate;
use AC\ColumnIterator;
use AC\ListScreenRepository\Storage;
use AC\Setting\ConfigFactory;
use AC\Type\ColumnId;
use AC\Type\ListScreenId;

class SettingUpdater
{

    private Storage $storage;

    private Aggregate $column_factory;

    private ConfigFactory $config_factory;

    public function __construct(Storage $storage, ConfigFactory $config_factory, Aggregate $column_factory)
    {
        $this->storage = $storage;
        $this->config_factory = $config_factory;
        $this->column_factory = $column_factory;
    }

    public function update(ListScreenId $list_id, ColumnId $column_id, array $settings): void
    {
        $list_screen = $this->storage->find($list_id);

        if ( ! $list_screen) {
            return;
        }

        $column = $list_screen->get_column($column_id);

        if ( ! $column) {
            return;
        }

        $factory = null;

        foreach ($this->column_factory->create($list_screen->get_table_screen()) as $candidate) {
            if ($candidate->get_column_type() === $column->get_type()) {
                $factory = $candidate;
                break;
            }
        }

        if ( ! $factory) {
            return;
        }

        $config = $this->config_factory->create($column)->with($settings);
        $column = $factory->create($config);

        $list_screen->set_columns($this->replace_in_collection($list_screen->get_columns(), $column));

        $this->storage->save($list_screen);
    }

    private function replace_in_collection(ColumnIterator $iterator, Column $updated): ColumnCollection
    {
        $columns = iterator_to_array($iterator);

        foreach ($columns as $k => $column) {
            if ($column->get_id()->equals($updated->get_id())) {
                $columns[$k] = $updated;
            }
        }

        return new ColumnCollection($columns);
    }

}
