<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class YesIcon implements Formatter
{

    private $class;

    public function __construct(?string $class = null)
    {
        $this->class = $class;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            $value->get_value()
                ? ac_helper()->icon->yes(null, null, $this->class)
                : false
        );
    }

}