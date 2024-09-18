<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class DottedPassword implements Formatter
{

    public function format(Value $value): Value
    {
        $raw_value = $value->get_value();

        if ( ! $raw_value || ! is_string($raw_value)) {
            return $value;
        }

        $char = '&#8226;';

        return $value->with_value(
            str_pad('', strlen($raw_value) * strlen($char), $char)
        );
    }

}