<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class Depth implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            count(get_post_ancestors($value->get_id())) + 1
        );
    }

}