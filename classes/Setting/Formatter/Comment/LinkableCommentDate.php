<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class LinkableCommentDate implements Formatter
{

    public function format(Value $value): Value
    {
        $label = sprintf(
            __('Submitted on <a href="%1$s">%2$s at %3$s</a>', 'codepress-admin-columns'),
            esc_url(get_comment_link($value->get_id())),
            ac_helper()->date->date($value->get_value()),
            ac_helper()->date->time($value->get_value())
        );

        return $value->with_value("<div class='submitted-on'>{$label}</div>");
    }

}