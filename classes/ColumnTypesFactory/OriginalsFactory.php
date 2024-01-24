<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\Column;
use AC\ColumnTypeCollection;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\TableScreen;

class OriginalsFactory implements AC\ColumnTypesFactory
{

    private $default_columns_repository;

    public function __construct(DefaultColumnsRepository $default_columns_repository)
    {
        $this->default_columns_repository = $default_columns_repository;
    }

    public function create(TableScreen $table_screen): ColumnTypeCollection
    {
        $columns = new ColumnTypeCollection();

        foreach ($this->default_columns_repository->find_all($table_screen->get_key()) as $type => $label) {
            $columns->add((new Column())->set_type($type)->set_label($label));
        }

        return $columns;
    }

}