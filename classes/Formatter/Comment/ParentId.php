<?php

declare(strict_types=1);

namespace AC\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use WP_Comment;

class ParentId implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());

        if ( ! $comment instanceof WP_Comment || ! $comment->comment_parent) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return new Value((int)$comment->comment_parent);
    }

}