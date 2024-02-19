<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class BeforeMoreContent implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post((int)$value->get_id());

        if ( ! $post) {
            return $value->with_value(false);
        }

        $content = '';

        $extended = get_extended($post->post_content);

        if ( ! empty($extended['extended'])) {
            $content = $extended['main'];
        }

        return $value->with_value($content);
    }

}