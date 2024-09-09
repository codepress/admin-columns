<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class CommentsForPostLink implements Formatter
{

    private $comment_status;

    public function __construct(string $comment_status)
    {
        $this->comment_status = $comment_status;
    }

    public function format(Value $value): Value
    {
        $link = ac_helper()->html->link(add_query_arg([
            'p'              => $value->get_id(),
            'comment_status' => $this->comment_status,
        ], admin_url('edit-comments.php')), (string)$value->get_value());

        return $value->with_value($link);
    }

}