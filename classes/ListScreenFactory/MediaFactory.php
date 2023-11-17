<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\ListScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;

// TODO remove
class MediaFactory extends BaseFactory
{

    public function __construct(TableScreenFactory\Media $table_screen_factory)
    {
        parent::__construct($table_screen_factory);
    }

    protected function create_list_screen(ListKey $key): ListScreen
    {
        return new ListScreen\Media($this->table_screen_factory->create($key));
    }

    public function can_create(ListKey $key): bool
    {
        return $this->table_screen_factory->can_create($key);
    }

}