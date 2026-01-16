<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class Trim implements Formatter
{

    public function format(Value $value): Value
    {
        if ('' === (string)$value) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            trim((string)$value)
        );
    }

}