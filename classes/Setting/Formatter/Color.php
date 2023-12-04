<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Color implements Formatter
{

    public function format(Value $value, ArrayImmutable $options): Value
    {
        return $value->with_value(
            ac_helper()->string->get_color_block((string)$value)
        );
    }

}