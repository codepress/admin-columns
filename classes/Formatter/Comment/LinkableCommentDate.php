<?php

declare(strict_types=1);

namespace AC\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Helper\Date;
use AC\Type\Value;
use DateTimeZone;

class LinkableCommentDate implements Formatter
{

    public function format(Value $value): Value
    {
        $timestamp = (int)$value->get_value();

        if ( ! $timestamp) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $date = wp_date(Date::create()->get_date_format(), $timestamp, new DateTimeZone('UTC')) ?: '';
        $time = wp_date(Date::create()->get_time_format(), $timestamp, new DateTimeZone('UTC')) ?: '';

        if ( ! $date || ! $time) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $label = sprintf(
            __('Submitted on <a href="%1$s">%2$s at %3$s</a>', 'codepress-admin-columns'),
            esc_url(get_comment_link($value->get_id())),
            $date,
            $time
        );

        return $value->with_value(
            sprintf("<div class='submitted-on'>%s</div>", $label)
        );
    }

}