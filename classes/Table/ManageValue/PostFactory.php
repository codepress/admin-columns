<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\PostType;
use AC\Registerable;
use AC\Table\GridRenderable;
use AC\Table\ManageValueFactory;
use AC\TableScreen;
use LogicException;

class PostFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof PostType;
    }

    public function create(
        GridRenderable $renderable,
        TableScreen $table_screen,
        int $priority = 100
    ): Registerable {
        if ( ! $table_screen instanceof PostType) {
            throw new LogicException('Invalid table screen.');
        }

        return new Post(
            $table_screen->get_post_type(),
            $renderable,
            $priority
        );
    }

}