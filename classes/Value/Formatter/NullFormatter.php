<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class NullFormatter implements Formatter
{

    public function format(Value $value): Value
    {
        return new Value(null);
    }

}