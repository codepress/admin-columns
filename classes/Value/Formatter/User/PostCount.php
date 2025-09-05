<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Value\Extended\ExtendedValue;

class PostCount implements Formatter
{

    private ?array $post_type;

    private ?array $post_stati;

    private ExtendedValue $extended_value;

    public function __construct(ExtendedValue $extended_value, array $post_type = [], ?array $post_stati = null)
    {
        $this->post_type = $post_type;
        $this->post_stati = $post_stati;
        $this->extended_value = $extended_value;
    }

    public function format(Value $value): Value
    {
        $count = $this->get_post_count(
            (int)$value->get_id()
        );

        if ($count < 1) {
            return $value->with_value(false);
        }

        $value = $value->with_value(
            number_format_i18n($count)
        );

        $post_types = $this->get_selected_post_types();

        // single post type
        if (1 === count($post_types)) {
            return (new UserFilteredPostLink($post_types[0]))->format($value);
        }

        return $value->with_value(
            $this->extended_value->get_link((int)$value->get_id(), (string)$count)
                                 ->with_title(ac_helper()->user->get_display_name($value->get_id()))
                                 ->render()
        );
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
        if (empty($this->post_type)) {
            // All post types, including the ones that are marked "exclude from search"
            return array_keys(get_post_types(['show_ui' => true]));
        }

        return $this->post_type;
    }

}