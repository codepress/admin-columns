<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Helper;
use AC\Helper\Date;
use AC\Type\Value;
use DateTimeZone;

class DatePublishFormatted implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post((int)$value->get_id());

        if ( ! $post) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        switch ($post->post_status) {
            // Icons
            case 'private' :
            case 'draft' :
            case 'pending' :
                return (new PostStatusIcon())->format(
                    new Value($post->ID, $post)
                );
            case 'future' :
                return $value->with_value(
                    sprintf(
                        '%s %s: <em>%s</em>',
                        (new PostStatusIcon())->format(new Value($post->ID, $post)),
                        __('Scheduled'),
                        (string)wp_date(
                            Date::create()->get_date_format(),
                            strtotime($post->post_date),
                            new DateTimeZone('UTC')
                        )
                    )
                );

            // Tooltip
            default :
                return $value->with_value(
                    Helper\Html::create()->tooltip(
                        (string)$value,
                        (string)wp_date(
                            Date::create()->get_date_time_format(),
                            strtotime($post->post_date),
                            new DateTimeZone('UTC')
                        )
                    )
                );
        }
    }

}