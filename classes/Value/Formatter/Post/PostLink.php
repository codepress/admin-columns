<?php

namespace AC\Value\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use WP_Post;

class PostLink implements Formatter
{

    private string $link_type;

    public function __construct(string $link_type)
    {
        $this->link_type = $link_type;
    }

    public function format(Value $value): Value
    {
        $post = get_post(
            $value->get_id()
        );

        if ( ! $post instanceof WP_Post) {
            throw ValueNotFoundException::from_id($value->get_id() ?? 0);
        }

        switch ($this->link_type) {
            case 'edit_post':
                $link = get_edit_post_link($post);

                break;
            case 'view_post' :
                $link = get_permalink($post);

                break;
            case 'edit_author':
                $link = get_edit_user_link($post->post_author);

                break;
            case 'view_author':
                $link = get_author_posts_url($post->post_author);

                break;
            default:
                $link = null;
        }

        return $link
            ? $value->with_value(ac_helper()->html->link($link, (string)$value))
            : $value;
    }

}