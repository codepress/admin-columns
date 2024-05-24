<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class MapToLabel implements Formatter
{

    private $formatter;

    private $mapping;

    public function __construct(Formatter $formatter, array $mapping)
    {
        $this->formatter = $formatter;
        $this->mapping = $mapping;
    }

    public function format(Value $value): Value
    {
        $raw_value = $this->formatter->format($value);

        if ( ! is_scalar($raw_value->get_value()) || ! array_key_exists($raw_value->get_value(), $this->mapping)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $this->mapping[$raw_value->get_value()]
        );
    }

}