<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Type\Value;

class DatePublishFormatted implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post((int)$value->get_id());

        if ( ! $post) {
            return new Value(null);
        }

        switch ($post->post_status) {
            // Icons
            case 'private' :
            case 'draft' :
            case 'pending' :
            case 'future' :
                return $value->with_value(ac_helper()->post->get_status_icon($post));

            // Tooltip
            default :
                return $value->with_value(
                    ac_helper()->html->tooltip(
                        (string)$value,
                        ac_helper()->date->date($post->post_date, 'wp_date_time')
                    )
                );
        }
    }

}