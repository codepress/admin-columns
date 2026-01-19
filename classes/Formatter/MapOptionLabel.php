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

        if ( ! is_scalar($raw_value)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        if ( ! $this->fallback_to_raw && ! array_key_exists($raw_value, $this->mapping)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $this->mapping[$raw_value] ?? $raw_value
        );
    }

}