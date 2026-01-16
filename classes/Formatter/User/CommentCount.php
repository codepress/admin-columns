<?php

declare(strict_types=1);

namespace AC\Formatter\User;

use AC\Formatter;
use AC\Type\Value;

class CommentCount implements Formatter
{

    public function format(Value $value): Value
    {
        $user_id = (int)$value->get_id();

        $count = $this->get_user_comments($user_id);

        return $count
            ? $value->with_value(
                sprintf(
                    '<a href="%s">%d</a>',
                    add_query_arg(['user_id' => $user_id], admin_url('edit-comments.php')),
                    $count
                )
            )
            : $value->with_value(false);
    }

    private function get_user_comments(int $user_id): int
    {
        return get_comments([
            'user_id' => $user_id,
            'count'   => true,
            'orderby' => false,
        ]);
    }

}