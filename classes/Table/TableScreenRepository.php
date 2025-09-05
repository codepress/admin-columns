<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Table\TableScreenRepository\Sort;
use AC\TableIdCollection;
use AC\TableIdsFactory;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\TableId;

class TableScreenRepository
{

    protected TableIdsFactory $table_ids_factory;

    protected TableScreenFactory $table_screen_factory;

    public function __construct(TableIdsFactory $table_ids_factory, TableScreenFactory $table_screen_factory)
    {
        $this->table_ids_factory = $table_ids_factory;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function find(TableId $table_id): ?TableScreen
    {
        return $this->table_screen_factory->can_create($table_id)
            ? $this->table_screen_factory->create($table_id)
            : null;
    }

    public function find_all_by_ids(TableIdCollection $ids, ?Sort $sort = null): TableScreenCollection
    {
        $table_screens = new TableScreenCollection();

        foreach ($ids as $key) {
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

    public function find_all(?Sort $sort = null): TableScreenCollection
    {
        $table_screens = new TableScreenCollection();

        foreach ($this->table_ids_factory->create() as $id) {
            if ( ! $this->table_screen_factory->can_create($id)) {
                continue;
            }

            $table_screens->add(
                $this->table_screen_factory->create($id)
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