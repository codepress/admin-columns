<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Formatter;
use AC\Setting\Type\Value;

final class ForeignId implements Formatter
{

    private $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            $this->formatter->format(
                new Value($value->get_value())
            )->get_value()
        );
    }

}