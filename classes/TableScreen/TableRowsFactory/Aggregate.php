<?php

declare(strict_types=1);

namespace AC\TableScreen\TableRowsFactory;

use AC\TableScreen;
use AC\TableScreen\TableRows;
use AC\TableScreen\TableRowsFactory;

class Aggregate implements TableRowsFactory
{

    /**
     * @var TableRowsFactory[]
     */
    private static $factories = [];

    public static function add(TableRowsFactory $factory): void
    {
        array_unshift(self::$factories, $factory);
    }

    public function create(TableScreen $table_screen): ?TableRows
    {
        foreach (self::$factories as $factory) {
            $table_rows = $factory->create($table_screen);

            if ($table_rows) {
                return $table_rows;
            }
        }

        return null;
    }

}