<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class PostDate implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post($value->get_id());

        return $value->with_value(
            $post->post_date ?? null
        );
    }

}