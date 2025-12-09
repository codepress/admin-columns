<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;
use Exception;

final class Append implements Formatter
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
        $appended_value = $value->get_value();
        try {
            $formatted = $this->formatter->format($value);
            if ($formatted) {
                $appended_value .= $this->separator . $formatted;
            }
        } catch (Exception $e) {

        }

        return $value->with_value($appended_value);
    }

}