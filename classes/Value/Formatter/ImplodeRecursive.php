<?php

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class ImplodeRecursive implements Formatter
{

    public function format(Value $value): Value
    {
        $array = $value->get_value();

        if ( ! is_array($array)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(ac_helper()->array->implode_recursive(__(', '), $array));
    }

}