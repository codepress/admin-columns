<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class IntegrationFactory implements ColumnFactories
{

    private $container;

    private $integration_repository;

    public function __construct(Container $container, AC\IntegrationRepository $integration_repository)
    {
        $this->container = $container;
        $this->repository = $integration_repository;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        $collection = new Collection\ColumnFactories();

        foreach ($this->repository->find_all_by_active_plugins() as $integration) {
            if ($integration->show_placeholder($table_screen)) {
                $factory = $this->container->make(AC\ColumnFactory\IntegrationPlaceholder::class, [
                    'integration' => $integration,
                ]);
                $collection->add($factory->get_column_type(), $factory);
            }
        }

        return $collection;
    }

}