<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;
use AC\Vendor\DI\Container;

final class IntegrationFactory extends BaseFactory
{

    private $repository;

    public function __construct(Container $container, AC\IntegrationRepository $integration_repository)
    {
        $this->repository = $integration_repository;
        parent::__construct($container);
    }

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();

        foreach ($this->repository->find_all_by_active_plugins() as $integration) {
            if ($integration->show_placeholder($table_screen)) {
                $collection->add(
                    new AC\Type\ColumnFactoryDefinition(
                        AC\ColumnFactory\IntegrationPlaceholder::class,
                        [
                            'integration' => $integration,
                        ]
                    )
                );
            }
        }

        return $collection;
    }

}