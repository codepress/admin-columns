<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

final class Prepend implements Formatter
{

    private $formatter;

    private $separator;

    public function __construct(Formatter $formatter, string $separator = '')
    {
        $this->formatter = $formatter;
        $this->separator = $separator;
    }

    public function format(Value $value): Value
    {
        $formatted = $this->formatter->format($value)->get_value();

        if ($formatted) {
            return $value->with_value($formatted . $this->separator . $value->get_value());
        }

        return $value;
    }

}