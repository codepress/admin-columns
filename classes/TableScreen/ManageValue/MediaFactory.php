<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\CellRenderer;
use AC\Registerable;
use AC\TableScreen;
use AC\TableScreen\ManageValueFactory;
use InvalidArgumentException;

class MediaFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Media;
    }

    public function create(TableScreen $table_screen, CellRenderer $renderable, int $priority = 100): Registerable
    {
        if ( ! $table_screen instanceof TableScreen\Media) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        return new Media($renderable, $priority);
    }

}