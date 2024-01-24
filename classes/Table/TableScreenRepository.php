<?php

declare(strict_types=1);

namespace AC\Table;

use AC\ListKeyCollection;
use AC\ListKeysFactory;
use AC\Table\TableScreenRepository\Sort;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;

class TableScreenRepository
{

    protected $list_keys_factory;

    protected $table_screen_factory;

    public function __construct(ListKeysFactory $list_keys_factory, TableScreenFactory $table_screen_factory)
    {
        $this->list_keys_factory = $list_keys_factory;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function find(ListKey $key): ?TableScreen
    {
        return $this->table_screen_factory->can_create($key)
            ? $this->table_screen_factory->create($key)
            : null;
    }

    public function find_all_by_list_keys(ListKeyCollection $list_keys, Sort $sort = null): TableScreenCollection
    {
        $table_screens = new TableScreenCollection();

        foreach ($list_keys as $key) {
            if ($this->table_screen_factory->can_create($key)) {
                $table_screens->add(
                    $this->table_screen_factory->create($key)
                );
            }
        }

        if ($sort) {
            $table_screens = $sort->sort($table_screens);
        }

        return $table_screens;
    }

    public function find_all(Sort $sort = null): TableScreenCollection
    {
        $table_screens = new TableScreenCollection();

        foreach ($this->list_keys_factory->create() as $key) {
            if ( ! $this->table_screen_factory->can_create($key)) {
                continue;
            }

            $table_screens->add(
                $this->table_screen_factory->create($key)
            );
        }

        if ($sort) {
            $table_screens = $sort->sort($table_screens);
        }

        return $table_screens;
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