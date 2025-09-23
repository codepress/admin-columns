<?php

declare(strict_types=1);

namespace AC\TableIdsFactory;

use AC\TableIdsFactory;
use AC\Type\TableIdCollection;

class Aggregate implements TableIdsFactory
{

    /**
     * @var TableIdsFactory[]
     */
    private static array $factories = [];

    public static function add(TableIdsFactory $factory): void
    {
        array_unshift(self::$factories, $factory);
    }

    public function create(): TableIdCollection
    {
        $ids = new TableIdCollection();

        foreach (self::$factories as $factory) {
            foreach ($factory->create() as $key) {
                $ids->add($key);
            }
        }

        return $ids;
    }

}