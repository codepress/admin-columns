<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

final class TimeStamp implements Formatter
{

    public function format(Value $value): Value
    {
        if (empty($value->get_value()) || ! is_scalar($value->get_value())) {
            return new Value(null);
        }

        if (is_numeric($value->get_value())) {
            return $value->with_value((int)$value->get_value());
        }

        $time = strtotime($value->get_value()) ?: null;

        return $time
            ? $value->with_value($time)
            : new Value(null);
    }

}