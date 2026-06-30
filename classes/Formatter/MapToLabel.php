<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class MapToLabel implements Formatter
{
    private Formatter $formatter;

    private array $mapping;

    public function __construct(Formatter $formatter, array $mapping)
    {
        $this->formatter = $formatter;
        $this->mapping = $mapping;
    }

    public function format(Value $value): Value
    {
        $raw_value = $this->formatter->format($value);

        if (! $raw_value instanceof Value) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $key = $raw_value->get_value();

        if (! is_scalar($key)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $key = is_bool($key) ? (int)$key : $key;

        if (! array_key_exists($key, $this->mapping)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $this->mapping[$key]
        );
    }

}
