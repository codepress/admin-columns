<?php

declare(strict_types=1);

namespace AC\Acf;

use AC\Column;
use AC\ListScreen;

class ColumnMatcher
{

    public function find_column(ListScreen $list_screen, string $meta_key): ?Column
    {
        foreach ($list_screen->get_columns() as $column) {
            if ($column->get_type() !== 'column-meta') {
                continue;
            }

            $setting = $column->get_setting('field');

            if ($setting && $setting->has_input() && (string)$setting->get_input()->get_value() === $meta_key) {
                return $column;
            }
        }

        return null;
    }

}
