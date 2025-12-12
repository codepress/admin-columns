<?php

declare(strict_types=1);

namespace AC;

use AC\Collection\ColumnFactories;
use AC\Column\ColumnFactory;
use AC\ColumnFactories\Aggregate;
use AC\Storage\Repository\OriginalColumnsRepository;
use AC\Type\TableId;

class ColumnTypeRepository
{

    private Aggregate $aggregate;

    private OriginalColumnsRepository $original_columns_repository;

    public function __construct(Aggregate $aggregate, OriginalColumnsRepository $original_columns_repository)
    {
        $this->aggregate = $aggregate;
        $this->original_columns_repository = $original_columns_repository;
    }

    private function find_column_factory(ColumnFactories $factories, string $type): ?ColumnFactory
    {
        foreach ($factories as $column_factory) {
            if ($type === $column_factory->get_column_type()) {
                return $column_factory;
            }
        }

        return null;
    }

    public function find(TableScreen $table_screen, string $type): ?Column
    {
        $factory = $this->find_column_factory($this->aggregate->create($table_screen), $type);

        if ( ! $factory) {
            return null;
        }

        return $factory->create(
            new Setting\Config([
                'label' => $factory->get_label(),
            ])
        );
    }

    public function find_all(TableScreen $table_screen): ColumnCollection
    {
        $columns = new ColumnCollection();

        foreach ($this->aggregate->create($table_screen) as $factory) {
            $columns->add($factory->create(new Setting\Config()));
        }

        return $columns;
    }

    private function get_originals(TableId $id): array
    {
        $column_names = [];

        foreach ($this->original_columns_repository->find_all_cached($id) as $column) {
            $column_names[$column->get_name()] = $column->get_label();
        }

        return $column_names;
    }

    public function find_all_by_original(TableScreen $table_screen): ColumnCollection
    {
        $columns = new ColumnCollection();

        $originals = $this->get_originals($table_screen->get_id());

        foreach ($this->aggregate->create($table_screen) as $factory) {
            $type = $factory->get_column_type();
            $label = $originals[$type] ?? null;

            if (null === $label) {
                continue;
            }

            $columns->add(
                $factory->create(
                    new Setting\Config([
                        'name'  => $type,
                        'label' => (string)$label,
                    ])
                )
            );
        }

        return $columns;
    }

}