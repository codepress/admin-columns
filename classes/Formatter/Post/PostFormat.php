<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class PostFormat implements Formatter
{

    public function format(Value $value): Value
    {
        $format = get_post_format($value->get_value());

        if ( ! $format) {
            throw new ValueNotFoundException('No Post format found for ID');
        }

        return $value->with_value($format);
    }

}