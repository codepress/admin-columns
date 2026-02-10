<?php

declare(strict_types=1);

namespace AC\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Helper;
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
            Helper\Html::create()->link(
                esc_url(get_comment_link($comment)),
                get_comment_author((int)$comment->comment_ID)
            )
        );
    }

}