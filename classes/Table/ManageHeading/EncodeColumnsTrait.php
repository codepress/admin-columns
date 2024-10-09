<?php

namespace AC\Table\ManageHeading;

use AC\ColumnRepository\Sort\ManualOrder;
use AC\ListScreen;

trait EncodeColumnsTrait
{

    protected function encode_columns(ListScreen $list_screen): array
    {
        $headings = [];

        $sort_strategy = new ManualOrder($list_screen->get_id());

        foreach ($sort_strategy->sort($list_screen->get_columns()) as $column) {
            $setting = $column->get_setting('label');

            $headings[(string)$column->get_id()] = $setting
                ? $setting->get_input()->get_value()
                : $column->get_label();
        }

        return $headings;
    }

}