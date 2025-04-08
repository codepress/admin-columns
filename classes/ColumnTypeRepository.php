<?php

declare(strict_types=1);

namespace AC;

use AC\ColumnFactories\Aggregate;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\Type\TableId;

class ColumnTypeRepository
{

    private Aggregate $aggregate;

    private DefaultColumnsRepository $default_columns_repository;

    public function __construct(Aggregate $aggregate, DefaultColumnsRepository $default_columns_repository)
    {
        $this->aggregate = $aggregate;
        $this->default_columns_repository = $default_columns_repository;
    }

    public function find(TableScreen $table_screen, string $type): ?Column
    {
        // TODO optimize
        $factory = iterator_to_array($this->aggregate->create($table_screen))[$type] ?? null;

        return $factory
            ? $factory->create(new Setting\Config())
            : null;
    }

    public function find_all(TableScreen $table_screen): ColumnCollection
    {
        $columns = new ColumnCollection();

        foreach ($this->aggregate->create($table_screen) as $factory) {
            $columns->add($factory->create(new Setting\Config()));
        }

        return $columns;
    }

    private function get_column_names(TableId $id): array
    {
        $column_names = [];

        foreach ($this->default_columns_repository->find_all($id) as $column) {
            $column_names[] = $column->get_name();
        }

        return $column_names;
    }

    public function find_all_by_original(TableScreen $table_screen): ColumnCollection
    {
        $columns = new ColumnCollection();

        $types = $this->get_column_names($table_screen->get_id());

        foreach ($this->aggregate->create($table_screen) as $type => $factory) {
            if ( ! in_array($type, $types, true)) {
                continue;
            }

            $columns->add(
                $factory->create(
                    new Setting\Config([
                        'name'  => $type,
                        'label' => $types[$type] ?? '',
                    ])
                )
            );
        }

        return $columns;
    }

}