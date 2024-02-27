<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class PostTitle implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_the_title($value->get_id()) // TODO fallback?
        );
    }

}