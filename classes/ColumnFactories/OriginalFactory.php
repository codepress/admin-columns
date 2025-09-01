<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC\ColumnFactory;
use AC\ColumnFactoryDefinitionCollection;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\TableScreen;
use AC\Type\ColumnFactoryDefinition;
use AC\Vendor\DI\Container;

final class OriginalFactory extends BaseFactory
{

    private DefaultColumnsRepository $repository;

    public function __construct(DefaultColumnsRepository $repository, Container $container)
    {
        $this->repository = $repository;

        parent::__construct($container);
    }

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();

        foreach ($this->repository->find_all_cached($table_screen->get_id()) as $column) {
            $collection->add(
                new ColumnFactoryDefinition(
                    ColumnFactory\OriginalFactory::class,
                    [
                        'type'  => $column->get_name(),
                        'label' => $column->get_label(),
                    ]
                )
            );
        }

        return $collection;
    }

}