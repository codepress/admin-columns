<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Type\Value;

class ParentId implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());

        return new Value($comment->comment_parent ?? null);
    }

}