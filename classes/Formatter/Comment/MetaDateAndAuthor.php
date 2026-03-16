<?php

declare(strict_types=1);

namespace AC\Formatter\Comment;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Helper;
use AC\Helper\Date;
use AC\Type\Value;
use WP_Comment;
use WP_User;

class MetaDateAndAuthor implements Formatter
{

    public function format(Value $value): Value
    {
        if ( ! $value->get_value()) {
            return $value;
        }

        $comment = get_comment($value->get_id());

        if ( ! $comment instanceof WP_Comment) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $date = sprintf(
            __('%s at %s', 'codepress-admin-columns'),
            wp_date(Date::create()->get_date_format(), strtotime($comment->comment_date_gmt)),
            wp_date(Date::create()->get_time_format(), strtotime($comment->comment_date_gmt))
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
        $user = get_userdata((int)$comment->user_id);

        if ($user instanceof WP_User) {
            return sprintf(
                '<a href="%s">%s</a>',
                add_query_arg('user_id', $comment->user_id, admin_url('edit-comments.php')),
                Helper\User::create()->get_formatted_name($user)
            );
        }

        return $comment->comment_author;
    }

}