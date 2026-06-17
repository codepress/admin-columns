<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Helper;
use AC\Helper\Date;
use AC\Type\Value;

class DatePublishFormatted implements Formatter
{

    public function format(Value $value): Value
    {
        $post = get_post((int)$value->get_id());

        if ( ! $post) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $date = (string)$value;

        switch ($post->post_status) {
            // Icons
            case 'private':
            case 'draft':
            case 'pending':
                return (new PostStatusIcon())->format(
                    new Value($post->ID, $post)
                );
            case 'future':
                return $value->with_value(
                    sprintf(
                        '%s %s: <em>%s</em>',
                        (new PostStatusIcon())->format(new Value($post->ID, $post)),
                        __('Scheduled'),
                        $date
                    )
                );

            // Tooltip
            default:
                $timestamp = strtotime($post->post_date_gmt);

                if (false === $timestamp) {
                    throw ValueNotFoundException::from_id($value->get_id());
                }

                return $value->with_value(
                    Helper\Html::create()->tooltip(
                        $date,
                        sprintf(
                            '%s <br><em>%s</em>',
                            __('Published'),
                            wp_date(
                                Date::create()->get_date_time_format(),
                                $timestamp
                            )
                        )
                    )
                );
        }
    }

}
