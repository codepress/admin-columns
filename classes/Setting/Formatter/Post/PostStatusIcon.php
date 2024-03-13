<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use WP_Post;

class PostStatusIcon implements Formatter
{

    public function format(Value $value): Value
    {
        $post = $value->get_value();

        if ( ! $post instanceof WP_Post) {
            return $value;
        }

        $html = ac_helper()->post->get_status_icon($value->get_value());

        if ($post->post_password) {
            $html .= ac_helper()->html->tooltip(
                ac_helper()->icon->dashicon(['icon' => 'lock', 'class' => 'gray']),
                __('Password protected')
            );
        }

        return $value->with_value($html);
    }

}