<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
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