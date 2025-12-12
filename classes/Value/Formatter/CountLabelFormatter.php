<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

final class CountLabelFormatter implements Formatter
{

    private string $singular;

    private string $plural;

    public function __construct(string $singular, string $plural)
    {
        $this->singular = $singular;
        $this->plural = $plural;
    }

    public function format(Value $value): Value
    {
        $count = $value->get_value();

        if ( ! is_numeric($count)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $text = ((int)$count === 1)
            ? $this->singular
            : $this->plural;

        return $value->with_value(
            sprintf($text, $count)
        );
    }

}