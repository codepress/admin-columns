<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class BeforeMoreContent implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post((int)$value->get_id());

        if ( ! $post) {
            return new Value(null);
        }

        $content = '';

        $extended = get_extended($post->post_content);

        if ( ! empty($extended['extended'])) {
            $content = $extended['main'];
        }

        return $value->with_value($content);
    }

}