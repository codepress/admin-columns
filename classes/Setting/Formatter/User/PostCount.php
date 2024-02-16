<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\User;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class PostCount implements Formatter
{

    private $post_type;

    private $post_stati;

    public function __construct(string $post_type = null, array $post_stati = null)
    {
        $this->post_type = $post_type;
        $this->post_stati = $post_stati;
    }

    public function format(Value $value): Value
    {
        $count = $this->get_post_count($value->get_id());

        if ($count < 1) {
            return $value->with_value(false);
        }

        $value = $value->with_value(number_format_i18n($count));

        $post_types = $this->get_selected_post_types();

        // single post type
        if (1 === count($post_types)) {
            return (new UserFilteredPostLink($post_types[0]))->format($value);
        }

        return $count < 0
            ? $value->with_value(false)
            : $value->with_value(number_format_i18n($count));
    }

    private function get_post_count(int $user_id): ?int
    {
        return ac_helper()->post->count_user_posts(
            $user_id,
            $this->get_selected_post_types(),
            $this->post_stati ?: get_post_stati(['internal' => 0])
        );
    }

    private function get_selected_post_types(): array
    {
        if ('any' === $this->post_type) {
            // All post types, including the ones that are marked "exclude from search"
            return array_keys(get_post_types(['show_ui' => true]));
        }

        if (post_type_exists($this->post_type)) {
            return [$this->post_type];
        }

        return [];
    }

}