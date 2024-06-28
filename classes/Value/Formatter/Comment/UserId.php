<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class UserId implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());
        $user_id = $comment->user_id ?? null;

        if ( ! $user_id) {
            throw ValueNotFoundException::from_id($value->get_value());
        }

        return new Value(
            $comment->user_id
        );
    }

}