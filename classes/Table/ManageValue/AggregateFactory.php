<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ColumnIterator;
use AC\Registerable;
use AC\Services;
use AC\Table\ManageValueFactory;
use AC\TableScreen;

class AggregateFactory
{

    private static array $factories = [];

    public static function add(ManageValueFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function create(TableScreen $table_screen, ColumnIterator $columns): ?Registerable
    {
        $services = new Services();

        foreach (self::$factories as $factory) {
            if ( ! $factory->can_create($table_screen)) {
                continue;
            }

            foreach ($columns as $column) {
                $services->add($factory->create($table_screen, $column));
            }
        }

        return $services;
    }

}