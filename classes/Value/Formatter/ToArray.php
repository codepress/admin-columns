<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class ToArray implements Formatter
{

    public function format(Value $value): Value
    {
        $current_value = $value->get_value();

        if (is_array($current_value)) {
            return $value;
        }

        // Anything not serialized, try to create an array from it
        if ( ! is_string($current_value)) {
            return $value->with_value((array)$current_value);
        }

        // JSON
        $result = json_decode($current_value, true);

        if (is_array($result)) {
            return $value->with_value($result);
        }

        // Serialized
        if (is_serialized($current_value)) {
            $result = unserialize($current_value, ['allowed_classes' => false]);

            if (is_array($result)) {
                return $value->with_value($result);
            }
        }

        // TODO Stefan in theory this can still be a string? That is ok?
        return $value->with_value($current_value);
    }

}