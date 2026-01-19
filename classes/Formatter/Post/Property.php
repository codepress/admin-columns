<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
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
        $field = get_post($value->get_id())->{$this->property} ?? null;

        if ( ! $field) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        if (in_array($this->property, ['post_author', 'post_parent'], true)) {
            if ($field === 0) {
                throw ValueNotFoundException::from_id($value->get_id());
            }

            return new Value((int)$field);
        }

        return $value->with_value(
            $field
        );
    }

}