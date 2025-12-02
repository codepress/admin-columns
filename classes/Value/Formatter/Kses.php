<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Sanitize;
use AC\Setting\Formatter;
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