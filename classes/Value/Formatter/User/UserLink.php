<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\PostTypeSlug;
use AC\Type\Value;

class UserLink implements Formatter
{

    private string $link_to;

    private ?PostTypeSlug $post_type;

    public function __construct(string $link_to, ?PostTypeSlug $post_type = null)
    {
        $this->link_to = $link_to;
        $this->post_type = $post_type;
    }

    public function format(Value $value): Value
    {
        $user_id = (int)$value->get_id();

        $user = get_userdata($user_id);

        if ( ! $user) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $link = '';

        switch ($this->link_to) {
            case 'edit_user':
                if (current_user_can('edit_users')) {
                    $link = add_query_arg('user_id', $user_id, self_admin_url('user-edit.php'));
                }
                break;
            case
            'view_user_posts' :
                $args['author'] = $user_id;

                if ($this->post_type) {
                    $args['post_type'] = (string)$this->post_type;
                }

                $link = add_query_arg($args, 'edit.php');
                break;
            case 'view_author':
                $link = get_author_posts_url($user_id);

                break;
            case 'email_user':
                $email = get_the_author_meta('email', $user_id);

                if ( ! $email) {
                    throw ValueNotFoundException::from_id($value->get_id());
                }

                $link = 'mailto:' . $email;

                break;
            default:
                return $value;
        }

        return $link
            ? $value->with_value(ac_helper()->html->link($link, (string)$value))
            : $value;
    }

}