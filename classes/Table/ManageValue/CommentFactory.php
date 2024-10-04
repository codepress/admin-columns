<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Registerable;
use AC\Table\GridRenderable;
use AC\Table\ManageValueFactory;
use AC\TableScreen;
use LogicException;

class CommentFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Comment;
    }

    public function create(GridRenderable $renderable, TableScreen $table_screen): Registerable
    {
        if ( ! $this->can_create($table_screen)) {
            throw new LogicException('Invalid table screen.');
        }

        return new Comment($renderable);
    }

}