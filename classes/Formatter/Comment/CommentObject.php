<?php

declare(strict_types=1);

namespace AC\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class CommentObject implements Formatter
{

    public function format(Value $value)
    {
        $comment = get_comment($value->get_id());

        if ( ! $comment) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($comment);
    }

}