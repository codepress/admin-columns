<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

final class TimeDifference implements Formatter
{

    public function format(Value $value): Value
    {
        $timestamp = $value->get_value();

        if ( ! is_numeric($timestamp) || 0 === (int)$timestamp) {
            throw new ValueNotFoundException();
        }

        $current_time = current_time('timestamp');

        $tpl = __('%s ago');

        if ($timestamp > $current_time) {
            $tpl = __('in %s', 'codepress-admin-columns');
        }

        return $value->with_value(sprintf($tpl, human_time_diff((int)$timestamp, $current_time)));
    }

}