<?php

declare(strict_types=1);

namespace AC\ColumnRepository\Filter;

use AC\Column;
use AC\ColumnCollection;
use AC\ColumnIterator;
use AC\ColumnRepository\Filter;

class ColumnSetting implements Filter
{

    private string $name;

    private string $value;

    public function __construct(string $name, string $value = 'on')
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function filter(ColumnIterator $columns): ColumnCollection
    {
        return new ColumnCollection(array_filter(iterator_to_array($columns), [$this, 'is_active']));
    }

    private function is_active(Column $column): bool
    {
        $setting = $column->get_setting($this->name);

        return $setting && $this->value === $setting->get_input()->get_value();
    }

}