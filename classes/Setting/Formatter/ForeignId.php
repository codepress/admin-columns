<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ForeignId implements Formatter
{

    public function format(Value $value): Value
    {
        if ($value->get_value() && is_numeric($value->get_value())) {
            return new Value($value->get_value());
        }

        throw new ValueNotFoundException();
    }

}