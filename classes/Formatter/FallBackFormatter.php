<?php

namespace AC\Formatter;

use AC;
use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class FallBackFormatter implements AC\Formatter
{

    private Formatter $formatter;

    private ?Formatter $fallback_formatter;

    public function __construct(Formatter $formatter, ?Formatter $fallback_formatter = null)
    {
        $this->formatter = $formatter;
        $this->fallback_formatter = $fallback_formatter;
    }

    public function format(Value $value): Value
    {
        try {
            $formatted_value = $this->formatter->format($value);
        } catch (ValueNotFoundException $e) {
            return $this->fallback_formatter
                ? $this->fallback_formatter->format($value)
                : $value;
        }

        return $formatted_value->get_value()
            ? $formatted_value
            : $this->fallback_formatter->format($value);
    }

}