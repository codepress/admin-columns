<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class Property implements Formatter
{

    private $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());
        $property = $comment->{$this->property} ?? null;

        if (null === $property) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($property);
    }

}