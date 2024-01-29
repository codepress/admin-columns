<?php

declare(strict_types=1);

namespace AC;

use AC\Column\Value;
use AC\Setting\Config;

// TODO remove
class ColumnPrototype
{

    private $options;

    private $column_type;

    public function __construct(Config $options, Column $column_type)
    {
        $this->options = $options;
        $this->column_type = $column_type;
    }

    public function get_options(): Config
    {
        return $this->options;
    }

    public function get_column_type(): Column
    {
        return $this->column_type;
    }

    public function renderable(Config $options): \AC\Column\Renderable
    {
        return $this->column_type instanceof Value
            ? $this->column_type->renderable()
            : null;
    }

}