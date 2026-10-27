<?php

declare(strict_types=1);

namespace AC\Formatter\Date;

use AC\Formatter;
use AC\Type\Value;
use DateTime;

final class TimeElement extends DateObject
{
    private Formatter $formatter;

    public function __construct(Formatter $formatter, string $source_format)
    {
        parent::__construct($source_format);

        $this->formatter = $formatter;
    }

    public function format(Value $value): Value
    {
        $formatted = $this->formatter->format($value);
        $date = parent::format($value)->get_value();

        if ('' === (string)$formatted || ! $date instanceof DateTime) {
            return $formatted;
        }

        $local_date = new DateTime($date->format('Y-m-d H:i:s'), wp_timezone());

        return $formatted->with_value(
            sprintf(
                '<time datetime="%s">%s</time>',
                esc_attr($local_date->format(DATE_ATOM)),
                $formatted
            )
        );
    }

}
