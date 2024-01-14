<?php

namespace AC;

trait ColumnNamesTrait
{

    public function get_column_names_from_collection(ColumnIterator $columns): array
    {
        return array_map(static function (Column $column) {
            return $column->get_name();
        }, iterator_to_array($columns));
    }

}