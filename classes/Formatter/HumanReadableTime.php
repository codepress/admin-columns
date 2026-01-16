<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

final class HumanReadableTime implements Formatter
{

    public function format(Value $value)
    {
        return $value->with_value(
            $this->make_human_readable(
                (int)$value->get_value()
            )
        );
    }

    private function make_human_readable(int $time_in_seconds): string
    {
        if ($time_in_seconds <= 0) {
            return '';
        }

        $minutes = floor($time_in_seconds / 60);
        $seconds = floor($time_in_seconds % 60);

        $time = $minutes;

        if ($minutes && $seconds < 10) {
            $seconds = '0' . $seconds;
        }

        if ('00' !== $seconds) {
            $time .= ':' . $seconds;
        }

        if ($minutes < 1) {
            $time = $seconds . ' ' . _n('second', 'seconds', $seconds, 'codepress-admin-columns');
        } else {
            $time .= ' ' . _n('minute', 'minutes', $minutes, 'codepress-admin-columns');
        }

        return $time;
    }

}