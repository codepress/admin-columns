<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Helper\Date;
use AC\Helper\Html;
use AC\Type\Value;
use DateTimeZone;
use WP_Post;

class DescriptivePostStatus implements Formatter
{

    public function format(Value $value): Value
    {
        global $wp_post_statuses;
        $post = get_post($value->get_id());

        if ( ! $post instanceof WP_Post) {
            return $value;
        }

        $status = $post->post_status;

        if (isset($wp_post_statuses[$status])) {
            $label = $wp_post_statuses[$status]->label;

            switch ($status) {
                case 'future':
                    $label = sprintf(
                        "%s: <em>%s</em>",
                        $label,
                        wp_date(
                            Date::create()->get_date_time_format(),
                            strtotime($post->post_date),
                            new DateTimeZone('UTC')
                        )
                    );

                    return $value->with_value(
                        $label
                    );
                case 'publish':
                    return $value->with_value(
                        Html::create()->tooltip(
                            $label,
                            wp_date(
                                Date::create()->get_date_time_format(),
                                strtotime($post->post_date),
                                new DateTimeZone('UTC')
                            ) ?: ''
                        )
                    );
                default:
                    return $value->with_value($label);
            }
        }

        return $value->with_value($status);
    }

}