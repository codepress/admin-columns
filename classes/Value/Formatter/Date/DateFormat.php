<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Setting\Formatter;
use AC\Type\Value;

final class DateFormat implements Formatter
{

    private $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function format(Value $value): Value
    {
        $timestamp = $value->get_value();

        if ( ! is_numeric($timestamp)) {
            return $value;
        }

        return $value->with_value(
            wp_date($this->format, (int)$timestamp)
        );
    }

}