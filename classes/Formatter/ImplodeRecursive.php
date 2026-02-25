<?php

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class ImplodeRecursive implements Formatter
{

    private ?string $glue;

    public function __construct(?string $glue = ', ')
    {
        $this->glue = $glue;
    }

    public function format(Value $value): Value
    {
        $current_value = $value->get_value();

        if (is_scalar($current_value)) {
            return $value->with_value((string)$current_value);
        }

        if ( ! is_array($current_value)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($this->implode_recursive($this->glue, $current_value));
    }

    private function implode_recursive(string $glue, array $pieces): string
    {
        $scalars = [];

        foreach ($pieces as $piece) {
            if (is_array($piece)) {
                $scalars[] = $this->implode_recursive($glue, $piece);
            }
            if (is_scalar($piece)) {
                $scalars[] = (string)$piece;
            }
        }

        return implode($glue, array_filter($scalars, static fn(string $v): bool => strlen($v) > 0));
    }

}