<?php

declare(strict_types=1);

namespace AC\Table;

use AC\ListKeysFactory;
use AC\TableScreen;
use AC\TableScreenFactory;

class TableScreenRepository
{

    protected $list_keys_factory;

    protected $table_screen_factory;

    public function __construct(ListKeysFactory $list_keys_factory, TableScreenFactory $table_screen_factory)
    {
        $this->list_keys_factory = $list_keys_factory;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function find_all(): TableScreenCollection
    {
        $table_screens = [];

        foreach ($this->list_keys_factory->create() as $key) {
            if ( ! $this->table_screen_factory->can_create($key)) {
                continue;
            }

            $table_screens[] = $this->table_screen_factory->create($key);
        }

        return new TableScreenCollection($table_screens);
    }

    public function find_all_site(): TableScreenCollection
    {
        return new TableScreenCollection(
            array_filter(iterator_to_array($this->find_all()), static function (TableScreen $screen): bool {
                return ! $screen->is_network();
            })
        );
    }

    public function find_all_network(): TableScreenCollection
    {
        return new TableScreenCollection(
            array_filter(iterator_to_array($this->find_all()), static function (TableScreen $screen): bool {
                return $screen->is_network();
            })
        );
    }

}