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
        $post = get_post($value->get_id());

        if ( ! $post) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $field = $post->{$this->property} ?? null;

        if (null === $field) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        if (in_array($this->property, ['post_author', 'post_parent'], true)) {
            if ($field === 0 || ! is_numeric($field)) {
                throw ValueNotFoundException::from_id($value->get_id());
            }

            return new Value((int)$field);
        }

        return $value->with_value(
            $field
        );
    }

}