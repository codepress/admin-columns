<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Type\Value;
use DateTime;
use InvalidArgumentException;

final class TimeDifference extends DateObject
{

    public function format(Value $value): Value
    {
        $date = parent::format($value)->get_value();

        if ( ! $date instanceof DateTime) {
            throw new InvalidArgumentException('Invalid date object');
        }

        $timestamp = $date->getTimestamp();
        $current_time = current_time('timestamp');

        $tpl = __('%s ago');

        if ($timestamp > $current_time) {
            $tpl = __('in %s', 'codepress-admin-columns');
        }

        return $value->with_value(
            sprintf($tpl, human_time_diff($timestamp, $current_time))
        );
    }

}