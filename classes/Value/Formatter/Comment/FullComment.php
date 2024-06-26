<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Type\Value;

class FullComment implements Formatter
{

    public function format(Value $value): Value
    {
        $comment = get_comment($value->get_id());
        $content = $comment->comment_content;

        $author = $comment->comment_author;
        if ($comment->comment_author_url) {
            $author = sprintf('<a href="%s">%s</a>', $comment->comment_author_url, $author);
        }

        $full_content = sprintf('%s<br><small>%s @ %s</small>', $content, $author, $comment->comment_date_gmt);

        return $value->with_value($full_content);
    }

}