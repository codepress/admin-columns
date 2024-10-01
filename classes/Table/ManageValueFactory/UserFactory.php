<?php

declare(strict_types=1);

namespace AC\Table\ManageValueFactory;

use AC\Column;
use AC\Registerable;
use AC\Table\ManageValue\User;
use AC\Table\ManageValueFactory;
use AC\TableScreen;
use LogicException;

class UserFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\User;
    }

    public function create(TableScreen $table_screen, Column $column): Registerable
    {
        if ( ! $this->can_create($table_screen)) {
            throw new LogicException('Invalid table screen.');
        }

        return new User($column);
    }

}