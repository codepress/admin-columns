<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

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

        return $property
            ? $value->with_value($property)
            : new Value(null);
    }

}