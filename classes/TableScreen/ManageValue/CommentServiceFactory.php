<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\Table\ManageValue\ValueFormatter;
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

    public function create(
        TableScreen $table_screen,
        ValueFormatter $formatter,
        int $priority = 100
    ): ManageValueService {
        if ( ! $table_screen instanceof TableScreen\Comment) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        return new Comment($formatter, $priority);
    }

}