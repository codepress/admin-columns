<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class MapOptionLabel implements Formatter
{
    private array $mapping;

    private bool $fallback_to_raw;

    public function __construct(array $mapping, bool $fallback_to_raw = false)
    {
        $this->mapping = $mapping;
        $this->fallback_to_raw = $fallback_to_raw;
    }

    public function format(Value $value): Value
    {
        $raw_value = $value->get_value();

        if (! is_scalar($raw_value)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $key = is_int($raw_value) || is_string($raw_value)
            ? $raw_value
            : (int)$raw_value;

        if (! $this->fallback_to_raw && ! array_key_exists($key, $this->mapping)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $this->mapping[$key] ?? $raw_value
        );
    }

}
