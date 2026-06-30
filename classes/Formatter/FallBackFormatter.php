<?php

declare(strict_types=1);

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
            return $this->format_fallback($value);
        }

        if ($formatted_value instanceof Value && $formatted_value->get_value()) {
            return $formatted_value;
        }

        return $this->format_fallback($value);
    }

    private function format_fallback(Value $value): Value
    {
        if (! $this->fallback_formatter) {
            return $value;
        }

        $fallback_value = $this->fallback_formatter->format($value);

        return $fallback_value instanceof Value
            ? $fallback_value
            : $value;
    }

}
