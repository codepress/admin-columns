<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\ColumnTypeCollection;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\TableScreen;

class Aggregate implements AC\ColumnTypesFactory
{

    /**
     * @var AC\ColumnTypesFactory[]
     */
    private static $factories = [];

    private $default_columns_repository;

    public function __construct(DefaultColumnsRepository $default_columns_repository)
    {
        $this->default_columns_repository = $default_columns_repository;
    }

    public static function add(AC\ColumnTypesFactory $factory): void
    {
        array_unshift(self::$factories, $factory);
    }

    public function create(TableScreen $table_screen): ColumnTypeCollection
    {
        $collection = new ColumnTypeCollection();

        foreach (self::$factories as $factory) {
            $_collection = $factory->create($table_screen);

            if ( ! $_collection) {
                continue;
            }

            foreach ($_collection as $column) {
                if ( ! $collection->contains($column)) {
                    $collection->add($column);
                }
            }
        }

        $this->modify_original_columns($table_screen, $collection);

        return $collection;
    }

    private function modify_original_columns(TableScreen $table_screen, ColumnTypeCollection $collection): void
    {
        $defaults = $this->default_columns_repository->find_all(
            $table_screen->get_key()
        );

        foreach ($collection as $column) {
            if ( ! $column->is_original()) {
                continue;
            }

            $label = $defaults[$column->get_type()] ?? null;

            // Remove non-existing originals
            if ( ! $label) {
                $collection->remove($column);
                continue;
            }

            // Add missing label to originals
            $column->set_label($label)
                   ->set_group('default');
        }

        // Sort by original order
        (new SortByTypes(array_keys($defaults)))->sort($collection);
    }

}