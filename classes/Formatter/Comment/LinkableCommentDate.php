<?php

declare(strict_types=1);

namespace AC\Formatter\Comment;

use AC\Formatter;
use AC\Type\Value;

class LinkableCommentDate implements Formatter
{

    public function format(Value $value): Value
    {
        $date = wp_date((string)get_option('date_format'), (string)$value);
        $time = wp_date((string)get_option('time_format'), (string)$value);

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