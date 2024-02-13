<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Path implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(str_replace(home_url(), '', get_permalink($value->get_id())));
    }

}