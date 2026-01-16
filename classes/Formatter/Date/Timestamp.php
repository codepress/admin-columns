<?php

declare(strict_types=1);

namespace AC\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

final class Timestamp implements Formatter
{

    public function format(Value $value): Value
    {
        if ($this->is_invalid_date((string)$value)) {
            throw new ValueNotFoundException('Invalid date string. ID ' . $value->get_id());
        }

        $date = (string)$value;

        if (is_numeric($date)) {
            return $value->with_value((int)$date);
        }

        $time = strtotime($date);

        if (false === $time) {
            throw new ValueNotFoundException('Invalid date string. ID ' . $value->get_id());
        }

        return $value->with_value($time);
    }

    private function is_invalid_date(string $date): bool
    {
        return ! $date || in_array($date, ['0000-00-00 00:00:00', '0000-00-00', '00:00:00'], true);
    }

}