<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\ColumnTypeCollection;
use AC\DefaultColumnsRepository;
use AC\TableScreen;

class Aggregate implements AC\ColumnTypesFactory
{

    /**
     * @var AC\ColumnTypesFactory[]
     */
    private static $factories = [];

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

        $this->modify_original_columns(new DefaultColumnsRepository($table_screen->get_key()), $collection);

        // TODO do_action('ac/column_type_collection', $collection, $table_screen);
        //  TODO check usages: apply_filters('ac/column_types', $this); and remove

        return $collection;
    }

    private function modify_original_columns(DefaultColumnsRepository $repo, ColumnTypeCollection $collection): void
    {
        // Remove non-existing originals
        foreach ($collection as $column_type) {
            if ($column_type->is_original() && ! $repo->find($column_type->get_type())) {
                $collection->remove($column_type);
            }
        }

        // Add missing labels to originals
        foreach ($collection as $column_type) {
            if ( ! $column_type->is_original()) {
                continue;
            }

            $original = $repo->find($column_type->get_type());

            if ($original) {
                $column_type->set_label($original->get_label())
                            ->set_group($original->get_group());
            }
        }
    }

}