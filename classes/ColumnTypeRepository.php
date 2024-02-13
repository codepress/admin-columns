<?php

declare(strict_types=1);

namespace AC;

use AC\ColumnFactories\Aggregate;
use AC\Storage\Repository\DefaultColumnsRepository;

class ColumnTypeRepository
{

    private $aggregate;

    private $default_columns_repository;

    public function __construct(Aggregate $aggregate, DefaultColumnsRepository $default_columns_repository)
    {
        $this->aggregate = $aggregate;
        $this->default_columns_repository = $default_columns_repository;
    }

    public function find(TableScreen $table_screen, string $type): ?Column
    {
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

    public function find_all_by_orginal(TableScreen $table_screen): ColumnCollection
    {
        $columns = new ColumnCollection();

        $types = $this->default_columns_repository->find_all($table_screen->get_key());

        foreach ($this->aggregate->create($table_screen) as $type => $factory) {
            $label = $types[$type] ?? null;

            if ( ! $label) {
                continue;
            }

            $columns->add(
                $factory->create(
                    new Setting\Config([
                        'name'  => $type,
                        'label' => $label,
                    ])
                )
            );
        }

        return $columns;
    }

}