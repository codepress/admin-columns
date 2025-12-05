<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class UrlDecode implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            urldecode((string)$value)
        );
    }

}