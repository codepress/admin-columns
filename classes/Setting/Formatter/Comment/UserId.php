<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class UserId implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());

        return new Value(
            $comment->user_id ?? null
        );
    }

}