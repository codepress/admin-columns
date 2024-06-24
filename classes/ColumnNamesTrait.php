<?php

namespace AC;

trait ColumnNamesTrait
{

    public function get_column_names_from_collection(ColumnIterator $columns): array
    {
        return array_map(static function (Column $column) {
            return (string)$column->get_id();
        }, iterator_to_array($columns));
    }

}