<?php

declare(strict_types=1);

namespace AC\ApplyFilter;

use AC\Setting\Context;

class ColumnValueSanitize
{

    private $id;

    private Context $context;

    public function __construct(Context $context, $id)
    {
        $this->id = $id;
        $this->context = $context;
    }

    public function apply_filter(bool $sanitize = true): bool
    {
        return (bool)apply_filters('ac/v2/column/value/sanitize', $sanitize, $this->context, $this->id);
    }

}