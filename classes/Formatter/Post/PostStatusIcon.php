<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Helper;
use AC\Type\Value;
use WP_Post;

class PostStatusIcon implements Formatter
{

    public function format(Value $value): Value
    {
        $post = $this->get_post_from_value($value);

        if ( ! $post instanceof WP_Post) {
            return $value;
        }

        return $value->with_value($this->get_status_icon($post));
    }

    private function get_post_from_value(Value $value): ?WP_Post
    {
        $post_value = $value->get_value();

        if ($post_value instanceof WP_Post) {
            return $post_value;
        }

        return get_post($value->get_id());
    }

    private function get_status_icon(WP_Post $post): ?string
    {
        switch ($post->post_status) {
            case 'private' :
                return Helper\Html::create()->tooltip(
                    Helper\Icon::create()->dashicon(['icon' => 'hidden', 'class' => 'gray']),
                    __('Private')
                );

            case 'publish' :
                return Helper\Html::create()->tooltip(
                    Helper\Icon::create()->dashicon(['icon' => 'yes', 'class' => 'blue large']),
                    __('Published')
                );

            case 'draft' :
                return Helper\Html::create()->tooltip(
                    Helper\Icon::create()->dashicon(['icon' => 'edit', 'class' => 'green']),
                    __('Draft')
                );

            case 'pending' :
                return Helper\Html::create()->tooltip(
                    Helper\Icon::create()->dashicon(['icon' => 'backup', 'class' => 'orange']),
                    __('Pending for review')
                );

            case 'future' :
                $format = get_option('date_format') . ' ' . get_option('time_format');
                $icon = Helper\Html::create()->tooltip(
                    Helper\Icon::create()->dashicon(['icon' => 'clock']),
                    __('Scheduled') . ': <em>' . date($format, strtotime($post->post_date)) . '</em>'
                );

                // Missed schedule
                if ((time() - mysql2date('G', $post->post_date_gmt)) > 0) {
                    $icon .= Helper\Html::create()->tooltip(
                        Helper\Icon::create()->dashicon(['icon' => 'flag', 'class' => 'gray']),
                        __('Missed schedule')
                    );
                }

                return $icon;
            default:
                return null;
        }
    }

}