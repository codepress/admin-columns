<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Value\Extended\Posts;
use WP_Post_Type;

class PostCount implements Formatter
{

    private array $post_types;

    private array $post_stati;

    public function __construct(array $post_types = [], ?array $post_stati = null)
    {
        $this->post_types = $post_types ?: array_keys(get_post_types(['show_ui' => true]));
        $this->post_stati = $post_stati ?: get_post_stati(['internal' => 0]);
    }

    public function format(Value $value): Value
    {
        $user_id = (int)$value->get_id();

        $user = get_userdata($user_id);

        if ( ! $user) {
            throw ValueNotFoundException::from_id($user_id);
        }

        $count = $this->get_post_count(
            $user_id
        );

        if ($count < 1) {
            return $value->with_value(false);
        }

        $single_posttype = $this->is_single_post_type();

        $params = [];

        if ($this->post_types) {
            $params['post_type'] = $this->post_types;
        }
        if ($this->post_stati) {
            $params['post_stati'] = $this->post_stati;
        }

        $label = (string)number_format_i18n($count);

        if ($single_posttype) {
            $label = sprintf(
                '%d %s',
                $count,
                $count > 1
                    ? strtolower($single_posttype->labels->name)
                    : strtolower($single_posttype->labels->singular_name)
            );
        }

        $username = ac_helper()->user->get_formatted_name($user);

        $link = (new Posts())->get_link($user_id, $label)
                             ->with_title(
                                 sprintf(
                                     __('Recent items by %s', 'codepress-admin-columns'),
                                     sprintf('”%s”', $username),
                                 )
                             )
                             ->with_params($params);

        if ($single_posttype) {
            $link = $link->with_view_link(
                add_query_arg([
                    'post_type' => $single_posttype->name,
                    'author'    => $user_id,
                ], admin_url('edit.php'))
            );
        }

        return $value->with_value(
            $link->render()
        );
    }

    private function is_single_post_type(): ?WP_Post_Type
    {
        return 1 === count($this->post_types)
            ? get_post_type_object($this->post_types[0])
            : null;
    }

    private function get_post_count(int $user_id): ?int
    {
        return ac_helper()->post->count_user_posts(
            $user_id,
            $this->post_types,
            $this->post_stati
        );
    }

}