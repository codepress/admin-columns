<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\CellRenderer;
use AC\TableScreen;
use AC\TableScreen\ManageValueService;
use AC\TableScreen\ManageValueServiceFactory;
use InvalidArgumentException;

class CommentServiceFactory implements ManageValueServiceFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Comment;
    }

    public function create(TableScreen $table_screen, CellRenderer $renderable, int $priority = 100): ManageValueService
    {
        if ( ! $table_screen instanceof TableScreen\Comment) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        return new Comment($renderable, $priority);
    }

}