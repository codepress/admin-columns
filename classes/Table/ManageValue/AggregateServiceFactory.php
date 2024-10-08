<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ListScreen;
use AC\Registerable;
use AC\Table\ManageValueServiceFactory;
use AC\TableScreen;

class AggregateServiceFactory implements ManageValueServiceFactory
{

    private static array $factories = [];

    public static function add(ManageValueServiceFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function create(TableScreen $table_screen, ListScreen $list_screen): ?Registerable
    {
        foreach (array_reverse(self::$factories) as $factory) {
            $service = $factory->create($table_screen, $list_screen);

            if ( ! $service) {
                continue;
            }

            return $service;
        }

        return null;
    }

}