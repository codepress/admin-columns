<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

final class Timestamp implements Formatter
{

    public function format(Value $value): Value
    {
        if (empty($value->get_value()) || ! is_scalar($value->get_value())) {
            throw new ValueNotFoundException();
        }

        if (is_numeric($value->get_value())) {
            return $value->with_value((int)$value->get_value());
        }

        $time = strtotime($value->get_value());

        if ( ! $time) {
            throw new ValueNotFoundException();
        }

        return $value->with_value($time);
    }

}