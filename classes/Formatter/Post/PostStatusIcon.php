<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
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
                return ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'hidden', 'class' => 'gray']),
                    __('Private')
                );

            case 'publish' :
                return ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'yes', 'class' => 'blue large']),
                    __('Published')
                );

            case 'draft' :
                return ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'edit', 'class' => 'green']),
                    __('Draft')
                );

            case 'pending' :
                return ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'backup', 'class' => 'orange']),
                    __('Pending for review')
                );

            case 'future' :
                $icon = ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'clock']),
                    __('Scheduled') . ': <em>' . ac_helper()->date->date($post->post_date, 'wp_date_time') . '</em>'
                );

                // Missed schedule
                if ((time() - mysql2date('G', $post->post_date_gmt)) > 0) {
                    $icon .= ac_helper()->html->tooltip(
                        ac_helper()->icon->dashicon(['icon' => 'flag', 'class' => 'gray']),
                        __('Missed schedule')
                    );
                }

                return $icon;
            default:
                return null;
        }
    }

}