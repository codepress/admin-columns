<?php

declare(strict_types=1);

namespace AC\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class CommentText implements Formatter
{

    public function format(Value $value)
    {
        $text = get_comment_text($value->get_id());

        if ( ! $text) {
            throw new ValueNotFoundException('Comment text not found for ID: ' . $value->get_id());
        }

        return $value->with_value(
            $text
        );
    }

}