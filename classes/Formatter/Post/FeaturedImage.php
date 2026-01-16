<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class FeaturedImage implements Formatter
{

    public function format(Value $value): Value
    {
        $thumbnail_id = get_post_thumbnail_id((int)$value->get_id());

        if ( ! $thumbnail_id) {
            throw new ValueNotFoundException('No thumbnail found for post ' . $value->get_id());
        }

        return new Value(
            $thumbnail_id
        );
    }

}