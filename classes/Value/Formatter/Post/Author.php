<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class Author implements Formatter
{

    public function format(Value $value): Value
    {
        return new Value(
            (int)get_post_field('post_author', (int)$value->get_id())
        );
    }

}