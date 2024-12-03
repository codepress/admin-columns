<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class CommentCount implements Formatter
{

    private string $comment_status;

    public function __construct(string $comment_status)
    {
        $this->comment_status = $comment_status ?: 'all';
    }

    public function format(Value $value): Value
    {
        $count = wp_count_comments($value->get_id())->{$this->comment_status} ?? null;

        if (null === $count) {
            throw new ValueNotFoundException();
        }

        return $value->with_value(
            (int)$count
        );
    }

}