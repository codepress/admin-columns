<?php

declare(strict_types=1);

namespace AC\ColumnRepository;

use AC\ColumnCollection;
use AC\ColumnFactory;
use AC\ColumnRepository;
use AC\Setting\Config;
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

        $factory = new \AC\ColumnFactories\Aggregate();

        $factories = $factory->create($this->table_screen);

        // TODO optimise
        foreach ($this->columns_data as $config) {
            foreach ($factories as $factory) {
                if ($factory->can_create($config['type'])) {
                    $columns->add(
                        $factory->create(
                            new Config($config)
                        )
                    );
                }
            }
        }

        return $columns;
    }

    //        foreach ($factories as $factory) {
    //            if ( $factory->can_create('column-meta') ) {
    //                $column = $factory->create(
    //        }
    //}

    //    public function find_all(): ColumnCollection
    //    {
    //        $columns = new ColumnCollection();
    //
    //        foreach ($this->columns_data as $column_data) {
    //            $column = $this->column_factory->create(
    //                $this->table_screen,
    //                new Config($column_data)
    //            );
    //
    //            if ($column) {
    //                $columns->add($column);
    //            }
    //        }
    //
    //        return $columns;
    //    }

}