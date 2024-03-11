<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use Countable;

class Count implements Formatter
{

    public function format(Value $value): Value
    {
        $count = 0;

        if ($value->get_value() instanceof Countable || is_array($value->get_value())) {
            $count = count($value->get_value());
        }

        return $value->with_value($count);
    }

}