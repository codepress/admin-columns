<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use WP_Comment;

class MetaDateAndAuthor implements Formatter
{

    public function format(Value $value): Value
    {
        if ( ! $value->get_value()) {
            return $value;
        }

        $comment = get_comment($value->get_id());

        if ( ! $comment) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $date = sprintf(
            __('%s at %s', 'codepress-admin-columns'),
            ac_helper()->date->date($comment->comment_date_gmt),
            ac_helper()->date->time($comment->comment_date_gmt)
        );

        $edit_link = get_edit_comment_link($comment);

        if ($edit_link) {
            $date = sprintf('<a href="%s">%s</a>', $edit_link, $date);
        }

        return $value->with_value(
            sprintf(
                '%s<br><small>%s @ %s</small>',
                $value->get_value(),
                $this->get_author($comment),
                $date
            )
        );
    }

    private function get_author(WP_Comment $comment): string
    {
        $user = get_userdata($comment->user_id);

        if ($user) {
            return sprintf(
                '<a href="%s">%s</a>',
                add_query_arg('user_id', $comment->user_id, admin_url('edit-comments.php')),
                ac_helper()->user->get_display_name($user)
            );
        }

        return $comment->comment_author;
    }

}