<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class IsSticky implements Formatter
{

    public function format(Value $value): Value
    {
        $is_sticky = in_array($value->get_id(), $this->get_stickies());

        return $value->with_value(ac_helper()->icon->yes_or_no($is_sticky));
    }

    private function get_stickies(): array
    {
        $posts = wp_cache_get('ac_sticky_posts');

        if ( ! $posts) {
            $posts = get_option('sticky_posts');
            wp_cache_set('ac_sticky_posts', $posts);
        }

        return (array)$posts;
    }

}