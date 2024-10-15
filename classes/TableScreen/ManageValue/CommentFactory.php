<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\Registerable;
use AC\TableScreen;
use AC\TableScreen\ManageValueFactory;
use InvalidArgumentException;

class CommentFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Comment;
    }

    public function create(TableScreen $table_screen, GridRenderable $renderable, int $priority = 100): Registerable
    {
        if ( ! $table_screen instanceof TableScreen\Comment) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        return new Comment($renderable, $priority);
    }

}