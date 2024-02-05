<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\ColumnFactories;
use AC\TableScreen;

// TODO Proof-of-concept POC
class Aggregate implements ColumnFactories
{

    /**
     * @var ColumnFactories[]
     */
    private static $factories = [];

    public static function add(ColumnFactories $factory): void
    {
        array_unshift(self::$factories, $factory);
    }

    public function create(TableScreen $table_screen): Collection\ColumnFactories
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