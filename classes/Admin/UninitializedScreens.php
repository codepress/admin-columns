<?php

declare(strict_types=1);

namespace AC\Admin;

use AC\DefaultColumnsRepository;
use AC\Table\TableScreensFactoryInterface;
use AC\TableScreen;

class UninitializedScreens
{

    private $table_screens_factory;

    public function __construct(TableScreensFactoryInterface $table_screens_factory)
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

    private function find_all(bool $is_network): array
    {
        $table_screens = $this->table_screens_factory->create()->all();

        $filter_callback = $is_network
            ? [$this, 'is_network']
            : [$this, 'is_site'];

        $table_screens = array_filter($table_screens, $filter_callback);
        $table_screens = array_filter($table_screens, [$this, 'exists']);

        return array_filter($table_screens);
    }

    private function exists(TableScreen $screen): bool
    {
        return ! (new DefaultColumnsRepository($screen->get_key()))->exists();
    }

    /**
     * @return TableScreen[]
     */
    public function find_all_network(): array
    {
        return $this->find_all(true);
    }

    /**
     * @return TableScreen[]
     */
    public function find_all_sites(): array
    {
        return $this->find_all(false);
    }

}