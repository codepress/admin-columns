<?php

declare(strict_types=1);

namespace AC\ColumnPreview;

use AC\TableScreen;

final class SampleItemsRegistry
{
    /**
     * @var SampleItems[]
     */
    private static array $items = [];

    public static function add(SampleItems $items): void
    {
        array_unshift(self::$items, $items);
    }

    public static function find(TableScreen $table_screen): ?SampleItems
    {
        foreach (self::$items as $items) {
            if ($items->supports($table_screen)) {
                return $items;
            }
        }

        return null;
    }

}
