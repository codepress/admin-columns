<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Type\Value;

class Content implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());

        if ( ! $comment->comment_content) {
            return $value;
        }

        return $value->with_value(
            strip_tags($comment->comment_content)
        );
    }

}