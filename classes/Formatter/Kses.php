<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Sanitize;
use AC\Type\Value;

class Kses implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            (new Sanitize\Kses())->sanitize((string)$value)
        );
    }

}