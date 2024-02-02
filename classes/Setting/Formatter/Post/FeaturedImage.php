<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class FeaturedImage implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_post_thumbnail_id($value->get_value()) ?: null
        );
    }

}