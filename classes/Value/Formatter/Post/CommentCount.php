<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class CommentCount implements Formatter
{

    private $comment_status;

    public function __construct(string $comment_status)
    {
        $this->comment_status = $comment_status;
    }

    public function format(Value $value): Value
    {
        return $value->with_value($this->get_comment_count($value->get_id()));
    }

    private function get_comment_count($post_id)
    {
        $status = $this->comment_status;

        $count = wp_count_comments($post_id);

        if (empty($count->{$status})) {
            return 0;
        }

        return (int)$count->{$status};
    }

}