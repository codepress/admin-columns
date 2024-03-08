<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Comment;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class CommentLink implements Formatter
{

    private $link_to;

    public function __construct(string $link_to)
    {
        $this->link_to = $link_to;
    }

    public function format(Value $value): Value
    {
        switch ($this->link_to) {
            case 'view_comment' :
                $link = get_comment_link($value->get_id());

                break;
            case 'edit_comment' :
                $comment = get_comment($value->get_id());

                $link = $comment
                    ? get_edit_comment_link($comment)
                    : false;

                break;
        }

        return $link
            ? $value->with_value(ac_helper()->html->link($link, $value->get_value()))
            : $value;
    }

}