<?php

declare(strict_types=1);

namespace AC\Table;

use AC\ListScreen;
use AC\Registerable;
use AC\Sanitize\Kses;
use AC\Setting\Type\Value;

abstract class ManageValue implements Registerable
{

    private $list_screen;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
    }

    // TODO
    public function render_cell(string $column_name, $id, string $fallback_value = null): ?string
    {
        $column = $this->list_screen->get_column($column_name);

        if ( ! $column) {
            return $fallback_value;
        }

        $value = $column->renderable()
                        ->format(new Value($id));

        // TODO
        if ( '' === $value->get_value()) {
            $value->with_value( '&ndash;' );
        }

        if (is_scalar($value->get_value())
            && apply_filters('ac/column/value/sanitize', true, $column, $id, $this->list_screen)) {
            $value->with_value( (new Kses())->sanitize((string)$value) );
        }

        // You can overwrite the display value for original columns by making sure get_value() does not return an empty string.
        if ($column->is_original() && ac_helper()->string->is_empty($value)) {
            return $fallback_value;
        }

        return (string)apply_filters('ac/column/value', (string)$value, $id, $column, $this->list_screen);
    }
}