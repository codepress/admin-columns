<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class Excerpt implements Formatter
{

    public function format(Value $value): Value
    {
        $excerpt = get_post((int)$value->get_id())->post_excerpt ?? null;

        if ( ! $excerpt) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $excerpt
        );
    }

}