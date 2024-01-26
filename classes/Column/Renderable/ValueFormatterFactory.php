<?php

declare(strict_types=1);

namespace AC\Column\Renderable;

use AC\Column;
use AC\Setting\ArrayImmutable;

class ValueFormatterFactory
{

    public function create(Column $column): ValueFormatter
    {
        return new ValueFormatter(
            $column->get_settings(),
            new ArrayImmutable($column->get_options()),
            $column->get_separator()
        );
    }

}