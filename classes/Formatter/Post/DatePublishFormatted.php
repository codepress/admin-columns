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
                return (new PostStatusIcon())->format(new Value($post->ID, $post));

            // Tooltip
            default :
                $format = get_option('date_format') . ' ' . get_option('time_format');

                return $value->with_value(
                    ac_helper()->html->tooltip(
                        (string)$value,
                        date($format, strtotime($post->post_date))
                    )
                );
        }
    }

}