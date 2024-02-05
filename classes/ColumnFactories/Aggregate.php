<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\ColumnFactories;
use AC\TableScreen;
use AC;

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
            $factories[] = iterator_to_array($factory->create($table_screen));
        }

        return new Collection\ColumnFactories(array_filter(array_merge(...$factories)));
    }

}