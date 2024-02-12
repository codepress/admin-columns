<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class DatePublishFormatted implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post($value->get_id());

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
                        $value->get_value(),
                        ac_helper()->date->date($post->post_date, 'wp_date_time')
                    )
                );
        }
    }

}