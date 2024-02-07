<?php

declare(strict_types=1);

namespace AC;

use AC\ColumnFactories\Aggregate;

class ColumnTypeRepository
{

    private $aggregate;

    public function __construct(Aggregate $aggregate)
    {
        $this->aggregate = $aggregate;
    }

    public function find(TableScreen $table_screen, string $type): ?Column
    {
        foreach ($this->aggregate->create($table_screen) as $factory) {
            if ($factory->can_create($type)) {
                return $factory->create(new Setting\Config());
            }
        }

        return null;
    }

    public function find_all(TableScreen $table_screen): ColumnCollection
    {
        $columns = new ColumnCollection();

        foreach ($this->aggregate->create($table_screen) as $factory) {
            $columns->add($factory->create(new Setting\Config()));
        }

        return $columns;
    }

    public function find_all_by_original(TableScreen $table_screen): ColumnCollection
    {
        $columns = new ColumnCollection();

        foreach ($this->find_all($table_screen) as $column) {
            if ($column->is_original()) {
                $columns->add($column);
            }
        }

        return $columns;
    }

}