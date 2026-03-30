<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class HumanTimeDifference implements Formatter
{

    public function format(Value $value)
    {
        $days = (int)$value->get_value();

        if ($days < 1) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            human_time_diff(0, $value->get_value() * DAY_IN_SECONDS)
        );
    }

}