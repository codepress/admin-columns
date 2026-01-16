<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

final class ConditionalValue implements Formatter
{

    private Value $check_value;

    private Formatter $formatter;

    public function __construct(Value $check_value, Formatter $formatter)
    {
        $this->check_value = $check_value;
        $this->formatter = $formatter;
    }

    public function format(Value $value): Value
    {
        if ($value->get_value() !== $this->check_value->get_value()) {
            return $value;
        }

        return $this->formatter->format($value);
    }

}