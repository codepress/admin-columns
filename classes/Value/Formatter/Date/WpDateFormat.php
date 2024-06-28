<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Setting\Formatter;
use AC\Type\Value;

final class WpDateFormat implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->date->format_date(get_option('date_format'), $value->get_value())
        );
    }

}