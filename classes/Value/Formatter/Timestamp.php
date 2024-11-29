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
        $date = $value->get_value();

        if (empty($date) || ! is_scalar($date)) {
            throw new ValueNotFoundException();
        }

        if (is_numeric($date)) {
            return $value->with_value((int)$date);
        }

        $time = strtotime($date);

        if ( ! $time) {
            throw new ValueNotFoundException();
        }

        return $value->with_value($time);
    }

}