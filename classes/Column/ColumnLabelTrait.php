<?php

namespace AC\Column;

use AC\Column;

trait ColumnLabelTrait
{

    public function get_column_label(Column $column): string
    {
        $label = $column->get_setting('label')->get_input()->get_value();

        if (false !== strpos($label, 'dashicons')) {
            return trim($label);
        }

        return trim(strip_tags($label))
            ?: trim(strip_tags($column->get_label()))
                ?: $column->get_type();
    }

}