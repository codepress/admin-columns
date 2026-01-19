<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class PostFormatLabel implements Formatter
{

    public function format(Value $value): Value
    {
        $label = get_post_format_string($value->get_value());

        if ( ! $label) {
            throw new ValueNotFoundException();
        }

        return $value->with_value($label);
    }

}