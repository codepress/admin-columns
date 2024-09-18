<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection\ColumnFactories;
use AC\ColumnFactoryCollectionFactory;
use AC\TableScreen;

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
        $factories = new ColumnFactories();

        foreach (self::$factories as $collection_factory) {
            foreach ($collection_factory->create($table_screen) as $factory) {
                $factories->add($factory);
            }
        }

        return $factories;
    }
}