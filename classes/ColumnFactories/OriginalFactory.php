<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\TableScreen;

class OriginalFactory implements ColumnFactories
{

    private $repository;

    public function __construct(DefaultColumnsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        $factories = [];

        foreach ($this->repository->find_all($table_screen->get_key()) as $type => $label) {
            $factories[$type] = new ColumnFactory\OriginalFactory($type, $label);
        }

        return $factories
            ? new Collection\ColumnFactories($factories)
            : null;
    }

}