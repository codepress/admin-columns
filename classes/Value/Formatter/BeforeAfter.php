<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class BeforeAfter implements Formatter
{

    private $before;

    private $after;

    public function __construct(string $before = null, string $after = null)
    {
        $this->before = (string)$before;
        $this->after = (string)$after;
    }

    public function format(Value $value): Value
    {
        if (is_scalar($value->get_value()) && ac_helper()->string->is_not_empty($value->get_value())) {
            $value = $value->with_value(
                $this->before . $value->get_value() . $this->after
            );
        }

        return $value;
    }

}