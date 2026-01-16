<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class ContentExcerpt implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post((int)$value->get_id());

        if ( ! $post) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $excerpt = get_the_excerpt($post);

        if ( ! $excerpt) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($excerpt);
    }

}