<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class CommentPost implements Formatter
{

    public function format(Value $value)
    {
        $post_id = get_commtent($value->get_id())->comment_post_ID ?? null;

        if (null === $post_id) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return new Value((int)$post_id);
    }

}