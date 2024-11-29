<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

final class WpDateFormat implements Formatter
{

    public function format(Value $value): Value
    {
        $timestamp = $value->get_value();

        if ( ! is_numeric($timestamp)) {
            throw new ValueNotFoundException();
        }

        return $value->with_value(
            wp_date((string)get_option('date_format'), $timestamp)
        );
    }

}