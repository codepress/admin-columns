<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Setting\Component\Input\Number;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class WordsPerMinute extends Settings\Column implements Formatter
{

    public function __construct(Specification $conditions = null)
    {
        parent::__construct(
            __('Words per minute', 'codepress-admin-columns'),
            __(
                'Estimated reading time in words per minute.',
                'codepress-admin-columns'
            ),
            Number::create_single_step('words_per_minute', 0, null, 200, ''),
            $conditions
        );
    }

    public function format(Value $value): Value
    {
        // TODO
        $time = $this->make_human_readable(
            $this->get_estimated_reading_time_in_seconds((string)$value, $options->get($this->name) ?: 200)
        );

        return $value->with_value($time);
    }

    protected function make_human_readable(int $seconds): string
    {
        if ($seconds <= 0) {
            return '';
        }

        $minutes = floor($seconds / 60);
        $seconds = floor($seconds % 60);

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

    protected function get_estimated_reading_time_in_seconds(string $string, int $words_per_minute): int
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