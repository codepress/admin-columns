<?php

declare(strict_types=1);

namespace AC\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use DateTime;

final class TimeElement implements Formatter
{
    private Formatter $formatter;

    private DateObject $date_object;

    public function __construct(Formatter $formatter, string $source_format)
    {
        $this->formatter = $formatter;
        $this->date_object = new DateObject($source_format);
    }

    public function format(Value $value): Value
    {
        $formatted = $this->formatter->format($value);

        if ('' === (string)$formatted) {
            return $formatted;
        }

        // A date formatter may read another input than the source format, its output still stands
        try {
            $date = $this->date_object->format($value)->get_value();
        } catch (ValueNotFoundException $e) {
            return $formatted;
        }

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
        // Date fields reach this as midnight, often through a timestamp
        if ('00:00:00' === $date->format('H:i:s')) {
            return $date->format('Y-m-d');
        }

        // Stored dates are the site's clock time, the attribute needs its offset
        return (new DateTime($date->format('Y-m-d H:i:s'), wp_timezone()))->format(DATE_ATOM);
    }

}
