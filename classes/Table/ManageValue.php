<?php

declare(strict_types=1);

namespace AC\Table;

use AC\ListScreen;
use AC\Registerable;
use AC\Sanitize\Kses;

abstract class ManageValue implements Registerable
{

    private $list_screen;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
    }

    public function render_cell(string $column_name, $id, string $fallback_value = null): ?string
    {
        $columns = $this->list_screen->get_columns();

        if ( ! $columns->exists($column_name)) {
            return $fallback_value;
        }

        $column = $columns->get($column_name);

        $value = $column->get_value($id);

        if (is_scalar($value) && apply_filters('ac/column/value/sanitize', true, $column, $id, $this->list_screen)) {
            $value = (new Kses())->sanitize((string)$value);
        }

        // You can overwrite the display value for original columns by making sure get_value() does not return an empty string.
        if ($column->is_original() && ac_helper()->string->is_empty($value)) {
            return $fallback_value;
        }

        return (string)apply_filters('ac/column/value', $value, $id, $column, $this->list_screen);
    }
}