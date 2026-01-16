<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Type\Value;

class IsSticky implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(in_array($value->get_id(), $this->get_stickies(), true));
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