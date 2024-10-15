<?php

declare(strict_types=1);

namespace AC\ApplyFilter;

use AC\Setting\Context;

class ColumnValue
{

    private $id;

    private Context $context;

    public function __construct(Context $context, $id)
    {
        $this->id = $id;
        $this->context = $context;
    }

    public function apply_filter(string $value = null): ?string
    {
        $value = apply_filters('ac/v2/column/value', $value, $this->id, $this->context);

        return is_scalar($value)
            ? (string)$value
            : null;
    }

}