<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\User;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use WP_Post;

class FirstPost implements Formatter
{

    public function format(Value $value): Value
    {
        $count = $this->get_first_post($value->get_id());

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

    private function get_first_post(int $user_id): ?WP_Post
    {
        $posts = get_posts([
            'author'      => $user_id,
            'fields'      => 'ids',
            'number'      => 1,
            'orderby'     => 'date',
            'post_status' => $this->get_related_post_stati(),
            'order'       => 'ASC',
            'post_type'   => $this->get_related_post_type(),
        ]);

        return empty($posts) ? null : $posts[0];
    }

}