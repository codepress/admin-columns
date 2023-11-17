<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\ListScreen\Comment;
use AC\TableScreenFactory;
use AC\Type\ListKey;

// TODO remove
class CommentFactory extends BaseFactory
{

    public function __construct(TableScreenFactory\Comment $table_screen_factory)
    {
        parent::__construct($table_screen_factory);
    }

    protected function create_list_screen(ListKey $key): ListScreen
    {
        return new Comment(
            $this->table_screen_factory->create($key)
        );
    }

    public function can_create(ListKey $key): bool
    {
        return $this->table_screen_factory->can_create($key);
    }

}