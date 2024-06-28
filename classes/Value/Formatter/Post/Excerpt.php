<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class Excerpt implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->post->excerpt((int)$value->get_id())
        );
    }

}