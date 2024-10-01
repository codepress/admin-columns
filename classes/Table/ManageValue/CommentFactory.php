<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Registerable;
use AC\Table\ManageValueFactory;
use AC\Table\Renderable;
use AC\TableScreen;
use AC\Type\ColumnId;
use LogicException;

class CommentFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Comment;
    }

    public function create(ColumnId $column_id, Renderable $renderable, TableScreen $table_screen): Registerable
    {
        if ( ! $table_screen instanceof TableScreen\Comment) {
            throw new LogicException('Invalid table screen.');
        }

        return new Comment($column_id, $renderable);
    }

}