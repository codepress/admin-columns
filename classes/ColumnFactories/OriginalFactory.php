<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class OriginalFactory implements ColumnFactories
{

    private $repository;

    private $container;

    public function __construct(DefaultColumnsRepository $repository, Container $container)
    {
        $this->repository = $repository;
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        $factories = [];

        foreach ($this->repository->find_all($table_screen->get_key()) as $type => $label) {
            $factories[$type] = $this->container->make(
                ColumnFactory\OriginalFactory::class,
                [
                    'type' => $type,
                    'label' => $label,
                ]
            );
        }

        return $factories
            ? new Collection\ColumnFactories($factories)
            : null;
    }

}