<?php

declare(strict_types=1);

namespace AC\ColumnRepository;

use AC\ColumnCollection;
use AC\ColumnFactory;
use AC\ColumnRepository;
use AC\TableScreen;

class EncodedData implements ColumnRepository
{

    private $column_factory;

    private $table_screen;

    private $columns_data;

    public function __construct(ColumnFactory $column_factory, TableScreen $table_screen, array $columns_data)
    {
        $this->column_factory = $column_factory;
        $this->table_screen = $table_screen;
        $this->columns_data = $columns_data;
    }

    public function find_all(): ColumnCollection
    {
        $columns = new ColumnCollection();

        foreach ($this->columns_data as $column_data) {
            $column = $this->column_factory->create($this->table_screen, $column_data);

            if ($column) {
                $columns->add($column);
            }
        }

        return $columns;
    }

}