<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ReplyToLink implements Formatter
{

    public function format(Value $value): Value
    {
        if ( ! $value->get_id()) {
            return $value->with_value(false);
        }
        $comment = get_comment($value->get_id());

        if ( ! $comment) {
            return $value->with_value(false);
        }

        return $value->with_value(
            ac_helper()->html->link(esc_url(get_comment_link($comment)), get_comment_author($comment->comment_ID))
        );
    }

}