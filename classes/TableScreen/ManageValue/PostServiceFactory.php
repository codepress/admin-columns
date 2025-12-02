<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\PostType;
use AC\Table\ManageValue\RenderFactory;
use AC\TableScreen;
use AC\TableScreen\ManageValueService;
use AC\TableScreen\ManageValueServiceFactory;
use InvalidArgumentException;

class PostServiceFactory implements ManageValueServiceFactory
{

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof PostType;
    }

    public function create(
        TableScreen $table_screen,
        RenderFactory $factory,
        int $priority = 100
    ): ManageValueService {
        if ( ! $table_screen instanceof PostType) {
            throw new InvalidArgumentException('Invalid table screen.');
        }

        return new Post(
            $table_screen->get_post_type(),
            $factory,
            $priority
        );
    }

}