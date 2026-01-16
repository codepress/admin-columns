<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Type\Value;

class Permalink implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_permalink($value->get_id())
        );
    }

}