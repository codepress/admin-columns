<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Column;
use AC\ColumnRepository;
use AC\Registerable;
use AC\Sanitize\Kses;

abstract class ManageValue implements Registerable
{

    private $column_repository;

    public function __construct(ColumnRepository $column_repository)
    {
        $this->column_repository = $column_repository;
    }

    public function render_cell(string $column_name, $id, string $fallback_value = null): ?string
    {
        $column = $this->column_repository->find($column_name);

        if ( ! $column) {
            return $fallback_value;
        }

        $value = $column->get_value($id);

        if (is_scalar($value) && apply_filters('ac/column/value/sanitize', true, $column, $id)) {
            $value = (new Kses())->sanitize((string)$value);
        }

        // You can overwrite the display value for original columns by making sure get_value() does not return an empty string.
        if ($column->is_original() && ac_helper()->string->is_empty($value)) {
            return $fallback_value;
        }

        /**
         * Column display value
         *
         * @param string $value  Column display value
         * @param int    $id     Object ID
         * @param Column $column Column object
         *
         * @since 3.0
         */
        return (string)apply_filters('ac/column/value', $value, $id, $column);
    }
}