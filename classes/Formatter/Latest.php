<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

final class Latest implements Formatter
{
    private Formatter $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function format(Value $value): Value
    {
        $result = $this->formatter->format($value);

        if ($result instanceof ValueCollection) {
            $last = $result->last();

            if (! $last instanceof Value) {
                throw ValueNotFoundException::from_id($value->get_id());
            }

            return $last;
        }

        return $result;
    }

}
