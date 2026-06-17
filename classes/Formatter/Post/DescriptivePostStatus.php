<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Helper\Date;
use AC\Helper\Html;
use AC\Type\Value;
use DateTimeZone;
use WP_Post;

// TODO Stefan still checks out?
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

        if ( ! isset($wp_post_statuses[$status])) {
            return $value->with_value($status);
        }

        $label = $wp_post_statuses[$status]->label;
        $timestamp = strtotime($post->post_date);

        if ($timestamp) {
            $formatted_date = wp_date(
                Date::create()->get_date_time_format(),
                $timestamp,
                new DateTimeZone('UTC')
            );

            switch ($status) {
                case 'future':
                    return $value->with_value(sprintf('%s: <em>%s</em>', $label, $formatted_date));
                case 'publish':
                    return $value->with_value(Html::create()->tooltip($label, $formatted_date ?: ''));
            }
        }

        return $value->with_value($label);
    }

}
