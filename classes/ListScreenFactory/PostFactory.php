<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Post;
use AC\TableScreenFactory;
use AC\Type\ListKey;

class PostFactory extends BaseFactory
{

    private $table_screen_factory;

    public function __construct(TableScreenFactory $table_screen_factory)
    {
        $this->table_screen_factory = $table_screen_factory;
    }

    protected function create_list_screen(string $key): ListScreen
    {
        return new Post(
            $this->table_screen_factory->create(new ListKey($key))
        );
    }

    public function can_create(string $key): bool
    {
        return $this->table_screen_factory->can_create(new ListKey($key));
    }

}