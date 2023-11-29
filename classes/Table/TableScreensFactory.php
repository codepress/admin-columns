<?php

declare(strict_types=1);

namespace AC\Table;

use AC\ListKeysFactory;
use AC\TableScreenFactory;

class TableScreensFactory
{

    protected $list_keys_factory;

    protected $table_screen_factory;

    public function __construct(ListKeysFactory $list_keys_factory, TableScreenFactory $table_screen_factory)
    {
        $this->list_keys_factory = $list_keys_factory;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function create(): TableScreenCollection
    {
        $collection = new TableScreenCollection();

        foreach ($this->list_keys_factory->create() as $key) {
            if ($this->table_screen_factory->can_create($key)) {
                $collection->add($this->table_screen_factory->create($key));
            }
        }

        return $collection;
    }

}