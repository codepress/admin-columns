<?php

declare(strict_types=1);

namespace AC\Response;

use AC\Column;
use AC\Setting\Encoder;

class JsonColumnFactory
{

    public function create_by_column(Column $column): Json
    {
        return (new Json())->set_parameter(
            'columns',
            [
                'settings' => $this->get_column_settings($column),
                'original' => $column->is_original(),
                'id'       => $column->get_name(),
            ]
        );
    }

    private function get_column_settings(Column $column): array
    {
        return (new Encoder($column->get_settings()))->encode();
    }

}