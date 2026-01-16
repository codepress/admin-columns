<?php

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class NumberFormat implements Formatter
{

    private $number_decimals;

    private $decimal_separator;

    private $thousands_separator;

    public function __construct(int $number_decimals, string $decimal_separator, string $thousands_separator)
    {
        $this->number_decimals = $number_decimals;
        $this->decimal_separator = $decimal_separator;
        $this->thousands_separator = $thousands_separator;
    }

    public function format(Value $value): Value
    {
        if ( ! is_numeric($value->get_value())) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            number_format(
                $value->get_value(),
                $this->number_decimals,
                $this->decimal_separator,
                $this->thousands_separator
            )
        );
    }

}