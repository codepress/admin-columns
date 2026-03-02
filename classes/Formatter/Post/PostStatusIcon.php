<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Helper;
use AC\Helper\Date;
use AC\Type\Value;
use DateTimeZone;
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
                    sprintf(
                        '%s <br><em>%s</em>',
                        __('Private'),
                        $this->format_date($post->post_date)
                    )
                );

            case 'publish' :
                return Helper\Html::create()->tooltip(
                    Helper\Icon::create()->dashicon(['icon' => 'yes', 'class' => 'blue large']),
                    sprintf(
                        '%s <br><em>%s</em>',
                        __('Published'),
                        $this->format_date($post->post_date)
                    )
                );

            case 'draft' :
                return Helper\Html::create()->tooltip(
                    Helper\Icon::create()->dashicon(['icon' => 'edit', 'class' => 'green']),
                    sprintf(
                        '%s <br><em>%s</em>',
                        __('Draft'),
                        $this->format_date($post->post_date)
                    )
                );

            case 'pending' :
                return Helper\Html::create()->tooltip(
                    Helper\Icon::create()->dashicon(['icon' => 'backup', 'class' => 'orange']),
                    sprintf(
                        '%s <br><em>%s</em>',
                        __('Pending for review'),
                        $this->format_date($post->post_date)
                    )
                );

            case 'future' :
                $icon = Helper\Html::create()->tooltip(
                    Helper\Icon::create()->dashicon(['icon' => 'clock']),
                    sprintf(
                        '%s <br><em>%s</em>',
                        __('Scheduled'),
                        $this->format_date($post->post_date)
                    )
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

    private function format_date(string $date): string
    {
        return wp_date(
            Date::create()->get_date_time_format(),
            strtotime($date),
            new DateTimeZone('UTC')
        ) ?: '';
    }

}