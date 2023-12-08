<?php

declare(strict_types=1);

namespace AC\Admin;

use AC\DefaultColumnsRepository;
use AC\Table\TableScreenCollection;
use AC\Table\TableScreenRepository;
use AC\TableScreen;

class UninitializedScreens
{

    private $table_screen_repository;

    public function __construct(TableScreenRepository $table_screen_repository)
    {
        $this->table_screen_repository = $table_screen_repository;
    }

    private function find_all(bool $is_network): TableScreenCollection
    {
        $collection = $is_network
            ? $this->table_screen_repository->find_all_network()
            : $this->table_screen_repository->find_all_site();

        $table_screens = array_filter(iterator_to_array($collection), [$this, 'is_uninitialized']);

        return new TableScreenCollection(array_filter($table_screens));
    }

    private function is_uninitialized(TableScreen $screen): bool
    {
        return ! (new DefaultColumnsRepository($screen->get_key()))->exists();
    }

    public function find_all_network(): TableScreenCollection
    {
        return $this->find_all(true);
    }

    public function find_all_site(): TableScreenCollection
    {
        return $this->find_all(false);
    }

}