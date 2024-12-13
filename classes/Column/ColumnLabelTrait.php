<?php

namespace AC\Column;

use AC\Column;

trait ColumnLabelTrait
{

    public function get_column_label(Column $column): string
    {
        $label = $column->get_setting('label')->get_input()->get_value();

        if (str_contains($label, 'dashicons')) {
            return trim($label);
        }

        // TODO reimplement hook ac/headings/label (used by WPML class in our codebase) (See old get_custom_label() function in $column

        return trim(strip_tags($label))
            ?: trim(strip_tags($column->get_label()))
                ?: $column->get_type();
    }

}