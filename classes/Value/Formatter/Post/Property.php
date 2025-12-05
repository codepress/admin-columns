<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class Property implements Formatter
{

    private string $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    public function format(Value $value): Value
    {
        $value = get_post($value->get_id())->{$this->property} ?? null;

        if ( ! $value) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $value
        );
    }

}