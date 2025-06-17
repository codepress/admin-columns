<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class NoIcon implements Formatter
{

    private ?string $class;

    public function __construct(?string $class = 'red')
    {
        $this->class = $class;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(ac_helper()->icon->no(null, null, $this->class));
    }

}