<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

final class Prepend implements Formatter
{

    private Formatter $formatter;

    private string $separator;

    public function __construct(Formatter $formatter, string $separator = '')
    {
        $this->formatter = $formatter;
        $this->separator = $separator;
    }

    public function format(Value $value): Value
    {
        $append = (string)$value;

        try {
            $prepend = (string)$this->formatter->format($value);

            if ($prepend && $append) {
                return $value->with_value($prepend . $this->separator . $append);
            }
        } catch (ValueNotFoundException $e) {
            return $value;
        }

        return $value;
    }

}