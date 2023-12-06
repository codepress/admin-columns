<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\SettingTrait;
use AC\Setting\Type\Value;
use AC\Settings;
use ACP\Expression\Specification;

class WordsPerMinute extends Settings\Column implements Formatter
{

    use SettingTrait;

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'words_per_minute';
        $this->label = __('Words per minute', 'codepress-admin-columns');
        $this->description = __(
            'Estimated reading time in words per minute.',
            'codepress-admin-columns'
        );
        $this->input = AC\Setting\Input\Number::create_single_step(0, null, 200, '');

        parent::__construct(
            $column,
            $conditions
        );
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $time = $this->make_human_readable(
            $this->get_estimated_reading_time_in_seconds($value, $options->get($this->name) ?: 200)
        );

        return $value->with_value($time);
    }

    protected function make_human_readable($seconds): string
    {
        $time = false;

        if (is_numeric($seconds)) {
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
        }

        return $time;
    }

    protected function get_estimated_reading_time_in_seconds($string, $words_per_minute): int
    {
        if ($words_per_minute <= 0) {
            return false;
        }

        $word_count = ac_helper()->string->word_count($string);

        if ( ! $word_count) {
            return false;
        }

        return (int)floor(($word_count / $words_per_minute) * 60);
    }
}