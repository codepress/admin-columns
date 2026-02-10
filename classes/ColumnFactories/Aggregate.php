<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection\ColumnFactories;
use AC\ColumnFactoryCollectionFactory;
use AC\TableScreen;

class Aggregate
{

    /**
     * @var ColumnFactoryCollectionFactory[]
     */
    private static array $factories = [];

    public static function add(ColumnFactoryCollectionFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function create(TableScreen $table_screen): ColumnFactories
    {
        static $factories;

        $id = (string)$table_screen->get_id();

        if ( ! isset($factories[$id])) {
            $factories[$id] = $this->get($table_screen);
        }

        return $factories[$id];
    }

    private function get(TableScreen $table_screen): ColumnFactories
    {
        $collection = new ColumnFactories();

        foreach (self::$factories as $collection_factory) {
            foreach ($collection_factory->create($table_screen) as $factory) {
                $collection->add($factory);
            }
        }

        return $collection;
    }
}