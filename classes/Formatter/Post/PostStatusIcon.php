<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Helper;
use AC\Helper\Dashicon;
use AC\Helper\WpDateFormat;
use AC\Type\Value;
use DateTimeZone;
use WP_Post;

class PostStatusIcon implements Formatter
{
    public function format(Value $value): Value
    {
        $post = $this->get_post_from_value($value);

        if (! $post instanceof WP_Post) {
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
        $formatted_date = $this->format_date($post->post_date);

        if ($formatted_date !== null) {
            switch ($post->post_status) {
                case 'private':
                    return Helper\Html::create()->tooltip(
                        Dashicon::create('hidden', 'gray')->render(),
                        sprintf(
                            '%s <br><em>%s</em>',
                            __('Private'),
                            $formatted_date
                        )
                    );

                case 'publish':
                    return Helper\Html::create()->tooltip(
                        Dashicon::create('yes', 'blue large')->render(),
                        sprintf(
                            '%s <br><em>%s</em>',
                            __('Published'),
                            $formatted_date
                        )
                    );

                case 'draft':
                    return Helper\Html::create()->tooltip(
                        Dashicon::create('edit', 'green')->render(),
                        sprintf(
                            '%s <br><em>%s</em>',
                            __('Draft'),
                            $formatted_date
                        )
                    );

                case 'pending':
                    return Helper\Html::create()->tooltip(
                        Dashicon::create('backup', 'orange')->render(),
                        sprintf(
                            '%s <br><em>%s</em>',
                            __('Pending for review'),
                            $formatted_date
                        )
                    );

                case 'future':
                    $icon = Helper\Html::create()->tooltip(
                        Dashicon::create('clock')->render(),
                        sprintf(
                            '%s <br><em>%s</em>',
                            __('Scheduled'),
                            $formatted_date
                        )
                    );

                    // Missed schedule
                    if ((time() - mysql2date('G', $post->post_date_gmt)) > 0) {
                        $icon .= Helper\Html::create()->tooltip(
                            Dashicon::create('flag', 'gray')->render(),
                            __('Missed schedule')
                        );
                    }

                    return $icon;
            }
        }

        return null;
    }

    private function format_date(string $date): ?string
    {
        $timestamp = strtotime($date);

        if ($timestamp === false) {
            return null;
        }

        return wp_date(
            WpDateFormat::date_time(),
            $timestamp,
            new DateTimeZone('UTC')
        ) ?: null;
    }

}
