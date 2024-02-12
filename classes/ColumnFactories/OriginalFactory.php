<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\TableScreen;

class OriginalFactory implements ColumnFactories
{

    private $repository;

    private $builder;

    public function __construct(DefaultColumnsRepository $repository, ComponentCollectionBuilderFactory $builder)
    {
        $this->repository = $repository;
        $this->builder = $builder;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        $factories = [];

        foreach ($this->repository->find_all($table_screen->get_key()) as $type => $label) {
            $factories[$type] = new ColumnFactory\OriginalFactory($type, $label, $this->builder->create());
        }

        return $factories
            ? new Collection\ColumnFactories($factories)
            : null;
    }

}