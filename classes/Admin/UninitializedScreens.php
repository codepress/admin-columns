<?php

declare(strict_types=1);

namespace AC\Admin;

use AC\DefaultColumnsRepository;
use AC\Table\TableScreenCollection;
use AC\Table\TableScreensFactory;
use AC\TableScreen;

class UninitializedScreens
{

    private $table_screens_factory;

    public function __construct(TableScreensFactory $table_screens_factory)
    {
        $this->table_screens_factory = $table_screens_factory;
    }

    private function is_network(TableScreen $table_screen): bool
    {
        return $table_screen->is_network();
    }

    private function is_site(TableScreen $table_screen): bool
    {
        return ! $table_screen->is_network();
    }

    private function find_all(bool $is_network): TableScreenCollection
    {
        $table_screens = iterator_to_array($this->table_screens_factory->create());

        $filter_callback = $is_network
            ? [$this, 'is_network']
            : [$this, 'is_site'];

        $table_screens = array_filter($table_screens, $filter_callback);
        $table_screens = array_filter($table_screens, [$this, 'exists']);

        return new TableScreenCollection(array_filter($table_screens));
    }

    private function exists(TableScreen $screen): bool
    {
        return ! (new DefaultColumnsRepository($screen->get_key()))->exists();
    }

    public function find_all_network(): TableScreenCollection
    {
        return $this->find_all(true);
    }

    public function find_all_sites(): TableScreenCollection
    {
        return $this->find_all(false);
    }

}