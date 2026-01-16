<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

final class Latest implements Formatter
{

    private $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function format(Value $value): Value
    {
        return $this->formatter->format($value);
    }

}