<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class ShortLink implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            wp_get_shortlink($value->get_id())
        );
    }

}