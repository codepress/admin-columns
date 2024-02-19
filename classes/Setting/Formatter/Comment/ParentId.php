<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ParentId implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());

        if ( ! $comment) {
            return new Value(null);
        }

        return new Value($comment->comment_parent);
    }

}