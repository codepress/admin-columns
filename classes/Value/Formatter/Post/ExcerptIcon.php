<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class ExcerptIcon implements Formatter
{

    public function format(Value $value): Value
    {
        $excerpt = $value->get_value();

        if ( ! $excerpt) {
            return $value;
        }

        $post = get_post((int)$value->get_id());

        if ( ! $post || $post->post_excerpt) {
            return $value;
        }

        $tooltip = ac_helper()->html->tooltip(
            ac_helper()->icon->dashicon(['icon' => 'media-text', 'class' => 'gray']),
            sprintf(
                '%s %s',
                __('Excerpt is missing.', 'codepress-admin-columns'),
                __('Current excerpt is generated from the content.', 'codepress-admin-columns')
            )
        );

        return $value->with_value($tooltip . $excerpt);
    }

}