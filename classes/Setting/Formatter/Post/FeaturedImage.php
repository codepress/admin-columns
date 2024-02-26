<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class FeaturedImage implements Formatter
{

    public function format(Value $value): Value
    {
        return new Value(
            get_post_thumbnail_id($value->get_id()) ?: null
        );
    }

}