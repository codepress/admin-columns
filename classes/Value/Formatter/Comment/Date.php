<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class Date implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_value());

        if ( ! $comment) {
            throw ValueNotFoundException::from_id($value->get_value());
        }

        $label = $comment->comment_date;
        $edit_link = get_edit_comment_link($comment);

        if ($edit_link) {
            $label = sprintf('<a href="%s">%s</a>', $edit_link, $label);
        }

        return $value->with_value(
            sprintf('<br><small><em>%s</em></small>', $label)
        );
    }

}