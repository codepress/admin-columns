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
        $field = $comment->{$this->property} ?? null;

        if (null === $field) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        if (in_array($this->property, ['comment_post_ID', 'comment_parent', 'user_id'], true)) {
            if ($field === 0) {
                throw ValueNotFoundException::from_id($value->get_id());
            }

            return new Value((int)$field);
        }

        return $value->with_value($field);
    }

}