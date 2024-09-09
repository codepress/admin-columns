<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\Collection\ColumnFactories;
use AC\ColumnFactoryCollectionFactory;
use AC\TableScreen;

// TODO Proof-of-concept POC
class Aggregate implements ColumnFactoryCollectionFactory
{

    /**
     * @var ColumnFactoryCollectionFactory[]
     */
    private static $factories = [];

    public static function add(ColumnFactoryCollectionFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function create(TableScreen $table_screen): ColumnFactories
    {
        $factories = [];

        foreach (self::$factories as $factory) {
            $column_factories = $factory->create($table_screen);

            if ($column_factories) {
                $factories[] = iterator_to_array($column_factories);
            }
        }

        return new Collection\ColumnFactories(
            array_merge(...$factories)
        );
    }

}