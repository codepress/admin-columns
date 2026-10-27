<?php

declare(strict_types=1);

namespace AC\Formatter\Date;

use AC\Formatter;
use AC\Type\Value;
use DateTime;

final class TimeElement implements Formatter
{
    private Formatter $formatter;

    private DateObject $date_object;

    private bool $has_time;

    public function __construct(Formatter $formatter, string $source_format)
    {
        $this->formatter = $formatter;
        $this->date_object = new DateObject($source_format);
        $this->has_time = 1 === preg_match('/(?<!\\\\)[aABgGhHisuvU]/', $source_format);
    }

    public function format(Value $value): Value
    {
        $formatted = $this->formatter->format($value);

        if ('' === (string)$formatted) {
            return $formatted;
        }

        $date = $this->date_object->format($value)->get_value();

        return $formatted->with_value(
            sprintf(
                '<time datetime="%s">%s</time>',
                esc_attr($this->get_datetime_attribute($date)),
                (string)$formatted
            )
        );
    }

    private function get_datetime_attribute(DateTime $date): string
    {
        // A date without a time is parsed with the current time filled in
        if (! $this->has_time) {
            return $date->format('Y-m-d');
        }

        // Stored dates are the site's clock time, the attribute needs its offset
        return (new DateTime($date->format('Y-m-d H:i:s'), wp_timezone()))->format(DATE_ATOM);
    }

}
