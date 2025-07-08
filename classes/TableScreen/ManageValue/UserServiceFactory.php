<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\CellRenderer;
use AC\TableScreen;
use AC\TableScreen\ManageValueService;
use AC\TableScreen\ManageValueServiceFactory;
use InvalidArgumentException;

class UserServiceFactory implements ManageValueServiceFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\User;
    }

    public function create(TableScreen $table_screen, CellRenderer $renderable, int $priority = 100): ManageValueService
    {
        if ( ! $table_screen instanceof TableScreen\User) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        return new User($renderable, $priority);
    }

}