<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC\Setting\Formatter;
use AC\Type\Value;

class FullName implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->user->get_display_name($value->get_id(), 'full_name') ?: ''
        );
    }

}