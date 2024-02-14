<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\User;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class CommentCount implements Formatter
{

    public function format(Value $value): Value
    {
        $count = $this->get_user_comments($value->get_id());

        return $count
            ? $value->with_value(
                sprintf(
                    '<a href="%s">%d</a>',
                    add_query_arg(['user_id' => $value->get_id()], admin_url('edit-comments.php')),
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