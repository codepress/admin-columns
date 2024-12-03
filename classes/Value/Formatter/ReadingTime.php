<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class ReadingTime implements Formatter
{

    private int $words_per_minute;

    public function __construct(int $words_per_minute)
    {
        $this->words_per_minute = $words_per_minute;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            $this->get_estimated_reading_time_in_seconds(
                (string)$value,
                $this->words_per_minute
            )
        );
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