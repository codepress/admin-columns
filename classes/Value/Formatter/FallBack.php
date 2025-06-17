<?php

namespace AC\Value\Formatter;

use AC;
use AC\Type\Value;

class FallBack implements AC\Setting\Formatter
{

    private string $fallback_value;

    public function __construct(string $fallback_value)
    {
        $this->fallback_value = $fallback_value;
    }

    public function format(Value $value): Value
    {
        return $value->get_value() ? $value : $value->with_value($this->fallback_value);
    }

}