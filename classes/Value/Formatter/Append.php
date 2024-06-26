<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class Append implements Formatter
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
        $formatted = $this->formatter->format($value);
        $appended_value = $value->get_value();

        if ($formatted) {
            $appended_value .= $this->separator . $formatted;
        }

        return $value->with_value($appended_value);
    }

}