<?php

declare(strict_types=1);

namespace AC\Table;

use AC\CellRenderer;
use AC\ListScreen;
use LogicException;

class ListScreenRenderableFactory implements CellRendererFactory
{

    private static CellRendererFactory $factory;

    public static function set(CellRendererFactory $factory): void
    {
        self::$factory = $factory;
    }

    public function create(ListScreen $list_screen): CellRenderer
    {
        if ( ! self::$factory instanceof CellRendererFactory) {
            throw new LogicException('Missing cell renderer factory');
        }

        return self::$factory->create($list_screen);
    }

}