<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
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