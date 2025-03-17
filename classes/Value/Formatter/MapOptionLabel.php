<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class MapOptionLabel implements Formatter
{

    private array $mapping;

    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public function format(Value $value): Value
    {
        $raw_value = $value->get_value();

        if ( ! is_scalar($raw_value) || ! array_key_exists($raw_value, $this->mapping)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $this->mapping[$raw_value]
        );
    }

}