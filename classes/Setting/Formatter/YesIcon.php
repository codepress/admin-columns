<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class YesIcon implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            (bool)$value->get_value() ? ac_helper()->icon->yes() : false
        );
    }

}