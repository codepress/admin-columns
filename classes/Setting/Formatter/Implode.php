<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Implode implements Formatter
{

    private $separator;

    public function __construct(string $separator = ', ')
    {
        $this->separator = $separator;
    }

    public function format(Value $value): Value
    {
        $values = $value->get_value();

        if ( ! is_array($values)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            implode($this->separator, $values)
        );
    }

}