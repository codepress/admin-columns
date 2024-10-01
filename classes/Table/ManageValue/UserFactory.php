<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Registerable;
use AC\Table\ManageValueFactory;
use AC\Table\Renderable;
use AC\TableScreen;
use AC\Type\ColumnId;
use LogicException;

class UserFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\User;
    }

    public function create(ColumnId $column_id, Renderable $renderable, TableScreen $table_screen): Registerable
    {
        if ( ! $this->can_create($table_screen)) {
            throw new LogicException('Invalid table screen.');
        }

        return new User($column_id, $renderable);
    }

}