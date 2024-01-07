<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC\Column\Placeholder;
use AC\ColumnTypeCollection;
use AC\ColumnTypesFactory;
use AC\IntegrationRepository;
use AC\TableScreen;

class IntegrationsFactory implements ColumnTypesFactory
{

    private $repository;

    public function __construct(IntegrationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(TableScreen $table_screen): ?ColumnTypeCollection
    {
        $collection = new ColumnTypeCollection();

        foreach ($this->repository->find_all_by_active_plugins() as $integration) {
            if ($integration->show_placeholder($table_screen)) {
                $collection->add((new Placeholder())->set_integration($integration));
            }
        }

        return $collection;
    }

}