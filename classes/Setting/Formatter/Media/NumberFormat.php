<?php

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class NumberFormat implements Formatter
{

    private $decimals;

    private $prefix;

    private $suffix;

    public function __construct(int $decimals = 0, string $prefix = '', string $suffix = '')
    {
        $this->decimals = $decimals;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    public function format(Value $value)
    {
        $number = $value->get_value();

        if ($number > 0) {
            $formatted_number = number_format($number, $this->decimals);

            return $value->with_value(
                sprintf('%s%s%s', $this->prefix, $formatted_number, $this->suffix)
            );
        }

        return $value;
    }

}