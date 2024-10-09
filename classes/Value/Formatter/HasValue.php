<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class HasValue implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value($value->get_value() !== null && $value->get_value() !== '');
    }

}