<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class HasValue implements Formatter
{

    private array $empty_values;

    public function __construct(?array $empty_values = null)
    {
        $this->empty_values = $empty_values ?? [null, '', false];
    }

    public function format(Value $value): Value
    {
        $boolean = ! in_array($value->get_value(), $this->empty_values, true);

        return $value->with_value($boolean);
    }

}