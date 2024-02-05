<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\ColumnTypeCollection;
use AC\TableScreen;

class Aggregate implements AC\ColumnTypesFactory
{

    private $aggregate;

    public function __construct(AC\ColumnFactories\Aggregate $aggregate)
    {
        $this->aggregate = $aggregate;
    }

    public function create(TableScreen $table_screen): ColumnTypeCollection
    {
        $columns = new ColumnTypeCollection();

        foreach ($this->aggregate->create($table_screen) as $factory) {
            $columns->add(
                $factory->create(new AC\Setting\Config())
            );
        }

        return $columns;
    }

}