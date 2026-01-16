<?php

namespace AC\Formatter;

use AC;
use AC\Type\Value;

class FallBack implements AC\Formatter
{

    private string $fallback_value;

    public function __construct(string $fallback_value)
    {
        $this->fallback_value = $fallback_value;
    }

    public function format(Value $value): Value
    {
        $current_value = trim($value->get_value());

        return $current_value ? $value : $value->with_value($this->fallback_value);
    }

}