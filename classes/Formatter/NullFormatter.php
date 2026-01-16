<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class NullFormatter implements Formatter
{

    public function format(Value $value): Value
    {
        return new Value(null);
    }

}