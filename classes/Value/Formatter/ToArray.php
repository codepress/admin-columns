<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class ToArray implements Formatter
{

    public function format(Value $value): Value
    {
        $rawValue = $value->get_value();

        if (is_array($rawValue)) {
            return $value;
        }

        if (is_object($rawValue)) {
            return $value->with_value((array)$rawValue);
        }

        if ( ! is_string($rawValue)) {
            return $value->with_value((array)$rawValue);
        }

        // JSON
        $result = json_decode($rawValue, true);

        if (is_array($result)) {
            return $value->with_value($result);
        }

        // Serialized
        if (is_serialized($rawValue)) {
            $result = unserialize($rawValue, ['allowed_classes' => false]);

            if (is_array($result)) {
                return $value->with_value($result);
            }
        }

        return $value->with_value($rawValue);
    }

}