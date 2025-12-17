<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use WP_Comment;

class ReplyToLink implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());

        if ( ! $comment instanceof WP_Comment) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            ac_helper()->html->link(
                esc_url(get_comment_link($comment)),
                get_comment_author($comment->comment_ID)
            )
        );
    }

}