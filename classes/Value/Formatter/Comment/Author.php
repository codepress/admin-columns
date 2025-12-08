<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Type\Value;

class Author implements Formatter
{

    public function format(Value $value)
    {
        return $value->with_value(
            get_comment_author($value->get_id())
        );
    }

}