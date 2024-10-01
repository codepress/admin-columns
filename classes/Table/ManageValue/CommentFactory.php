<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\Registerable;
use AC\Table\ManageValueFactory;
use AC\TableScreen;
use LogicException;

class CommentFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Comment;
    }

    public function create(TableScreen $table_screen, Column $column): Registerable
    {
        if ( ! $table_screen instanceof TableScreen\Comment) {
            throw new LogicException('Invalid table screen.');
        }

        return new Comment($column);
    }

}