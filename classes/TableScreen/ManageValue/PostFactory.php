<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\PostType;
use AC\Registerable;
use AC\TableScreen;
use AC\TableScreen\ManageValueFactory;
use InvalidArgumentException;

class PostFactory implements ManageValueFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof PostType;
    }

    public function create(
        TableScreen $table_screen,
        GridRenderable $renderable,
        int $priority = 100
    ): Registerable {
        if ( ! $table_screen instanceof PostType) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        return new Post(
            $table_screen->get_post_type(),
            $renderable,
            $priority
        );
    }

}