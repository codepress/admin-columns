<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use WP_Post;

class DescriptivePostStatus implements Formatter
{

    public function format(Value $value): Value
    {
        $post = $value->get_value();

        if ( ! $post instanceof WP_Post) {
            return $value;
        }

        $status = $post->post_status;

        if (isset($wp_post_statuses[$status])) {
            $html = $wp_post_statuses[$status]->label;

            if ('future' === $status) {
                $html = sprintf(
                    "%s <p class='description'>%s</p>",
                    $html,
                    ac_helper()->date->date($post->post_date, 'wp_date_time')
                );
            }

            return $value->with_value($html);
        }

        return $value->with_value($status);
    }

}