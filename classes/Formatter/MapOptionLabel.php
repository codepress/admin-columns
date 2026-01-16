<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
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
        $label = $this->mapping[$value->get_value()] ?? null;

        if ( ! $label) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($label);
    }

}