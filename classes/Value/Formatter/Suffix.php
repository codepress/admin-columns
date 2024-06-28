<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class Suffix implements Formatter
{

    private $suffix;

    public function __construct($suffix)
    {
        $this->suffix = $suffix;
    }

    public function format(Value $value): Value
    {
        return $value->get_value()
            ? $value->with_value($value->get_value() . $this->suffix)
            : $value;
    }

}