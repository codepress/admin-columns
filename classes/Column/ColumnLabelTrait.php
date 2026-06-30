<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;

trait ColumnLabelTrait
{
    public function get_column_label(Column $column): string
    {
        $setting = $column->get_setting('label');
        $label = $setting
            ? (string)$setting->get_input()->get_value()
            : '';

        if (str_contains($label, 'dashicons')) {
            return trim($label);
        }

        return trim(strip_tags($label))
            ?: trim(strip_tags($column->get_label()))
                ?: $column->get_type();
    }

}
