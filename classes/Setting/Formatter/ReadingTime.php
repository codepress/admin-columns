<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

final class ReadingTime implements Formatter
{

    private $words_per_minute;

    public function __construct(int $words_per_minute)
    {
        $this->words_per_minute = $words_per_minute;
    }

    public function format(Value $value): Value
    {
        $reading_time = $this->make_human_readable(
            $this->get_estimated_reading_time_in_seconds(
                (string)$value,
                $this->words_per_minute
            )
        );

        return $value->with_value($reading_time);
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

    private function get_estimated_reading_time_in_seconds(string $string, int $words_per_minute): int
    {
        if ($words_per_minute <= 0) {
            return 0;
        }

        $word_count = ac_helper()->string->word_count($string);

        if ( ! $word_count) {
            return 0;
        }

        return (int)floor(($word_count / $words_per_minute) * 60);
    }

}