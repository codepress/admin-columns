<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Id implements Formatter
{

    public function format(Value $value): Value
    {
        return new Value((int)$value->get_value());
    }

}