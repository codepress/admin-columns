<?php

namespace AC\Value\Formatter;

use AC;
use AC\Setting\Formatter;
use AC\Type\Value;

class FallBackFormatter implements AC\Setting\Formatter
{

    private Formatter $fallback_formatter;

    public function __construct(Formatter $formatter)
    {
        $this->fallback_formatter = $formatter;
    }

    public function format(Value $value): Value
    {
        return $value->get_value()
            ? $value
            : $this->fallback_formatter->format($value);
    }

}