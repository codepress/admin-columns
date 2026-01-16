<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use WP_Post;

class PostTitle implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post($value->get_id());

        if ( ! $post) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $this->get_title($post)
        );
    }

    private function get_title(WP_Post $post): string
    {
        if ('attachment' === $post->post_type) {
            return ac_helper()->image->get_file_name($post->ID) ?: '';
        }

        return get_the_title($post);
    }

}