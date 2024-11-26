<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class ContentExcerpt implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post((int)$value->get_id());

        if ( ! $post) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $excerpt = ac_helper()->post->excerpt($post->ID);

        if ( ! $excerpt) {
            return $value;
        }

        if ($post->post_excerpt) {
            return $value->with_value($excerpt);
        }

        if ($post->post_content) {
            $tooltip = ac_helper()->html->tooltip(
                ac_helper()->icon->dashicon(['icon' => 'media-text', 'class' => 'gray']),
                __('Excerpt is missing.') . ' ' . __('Current excerpt is generated from the content.')
            );

            return $value->with_value($tooltip . $excerpt);
        }

        return $value;
    }

}