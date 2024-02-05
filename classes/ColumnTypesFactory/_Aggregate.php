<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\ColumnTypeCollection;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\TableScreen;

class _Aggregate implements AC\ColumnTypesFactory
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
        $columns = [];

        foreach (self::$factories as $factory) {
            $collection = $factory->create($table_screen);

            if ( ! $collection) {
                continue;
            }

            foreach ($collection as $column) {
                if (isset($columns[$column->get_type()])) {
                    continue;
                }

                $columns[$column->get_type()] = $column;
            }
        }

        $columns = $this->modify_original_columns(
            $table_screen,
            $columns
        );

        return new ColumnTypeCollection($columns);
    }

    private function modify_original_columns(TableScreen $table_screen, array $columns): array
    {
        $defaults = $this->default_columns_repository->find_all(
            $table_screen->get_key()
        );

        foreach ($columns as $type => $column) {
            if ( ! $column->is_original()) {
                continue;
            }

            $label = $defaults[$type] ?? null;

            // Remove non-existing originals
            if (null === $label) {
                unset($columns[$type]);
                continue;
            }

            // Add missing label to originals
            $column->set_label($label)
                   ->set_group('default');
        }

        // Sort by original order
        return (new SortByTypes(array_keys($defaults)))->sort($columns);
    }

}