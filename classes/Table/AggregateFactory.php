<?php

declare(strict_types=1);

namespace AC\Table;

use AC\ListScreen;
use AC\Registerable;
use AC\Services;
use AC\TableScreen;

// TODO only works on Columns. Create a separate one for Conditional Formatting.
class AggregateFactory
{

    /**
     * @var ManageValueFactory[]
     */
    private static array $factories = [];

    public static function add(ManageValueFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function create(TableScreen $table_screen, ListScreen $list_screen): ?Registerable
    {
        $services = new Services();

        foreach (self::$factories as $factory) {
            if ( ! $factory->can_create($table_screen)) {
                continue;
            }

            $services->add(
                $factory->create(
                    new GridRenderable\ColumnRenderable($list_screen),
                    $table_screen
                )
            );
        }

        return $services;
    }

}