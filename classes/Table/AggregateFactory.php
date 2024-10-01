<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Column;
use AC\ColumnIterator;
use AC\Registerable;
use AC\Services;
use AC\Table\Renderable\ColumnRenderable;
use AC\TableScreen;

// TODO only works on Columns
class AggregateFactory
{

    /**
     * @var ManageValueFactory[]
     */
    private static array $factories = [];

    public static function add(ManageValueFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function create(TableScreen $table_screen, ColumnIterator $columns): ?Registerable
    {
        $services = new Services();

        /**
         * @var Column $column
         */
        foreach (self::$factories as $factory) {
            if ( ! $factory->can_create($table_screen)) {
                continue;
            }

            foreach ($columns as $column) {
                $formatters = $column->get_formatters();

                $renderable = $formatters->valid()
                    ? new ColumnRenderable($formatters)
                    : null;

                // TODO.. should we wrap $renderable in a filter for external access?
                $renderable = apply_filters('ac/v2/column/value', $renderable, $column, $factory);

                if ( ! $renderable) {
                    continue;
                }

                $service = $factory->create(
                    $column->get_id(),
                    $renderable,
                    $table_screen
                );

                $services->add($service);
            }
        }

        return $services;
    }

}