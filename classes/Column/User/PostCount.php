<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

class PostCount extends Column
{

    public function __construct()
    {
        $this->set_type('column-user_postcount')
             ->set_label(__('Post Count', 'codepress-admin-columns'));
    }

    public function get_value($user_id)
    {
        $user_id = (int)$user_id;

        $count = $this->get_post_count($user_id);

        if ($count < 1) {
            return $this->get_empty_char();
        }

        $value = number_format_i18n($count);

        $post_types = $this->get_selected_post_types();

        // single post type
        if (1 === count($post_types)) {
            $link = add_query_arg([
                'post_type' => $post_types[0],
                'author'    => $user_id,
            ], admin_url('edit.php'));

            $value = sprintf('<a href="%s">%s</a>', $link, $value);
        }

        return $value;
    }

    private function get_post_count(int $user_id): int
    {
        return ac_helper()->post->count_user_posts(
            $user_id,
            $this->get_selected_post_types(),
            $this->get_selected_post_status()
        );
    }

    protected function get_selected_post_types(): array
    {
        $post_type = (string)$this->get_setting('post_type')->get_post_type();

        if ('any' === $post_type) {
            // All post types, including the ones that are marked "exclude from search"
            return array_keys(get_post_types(['show_ui' => true]));
        }

        if (post_type_exists($post_type)) {
            return [$post_type];
        }

        return [];
    }

    public function get_raw_value($user_id)
    {
        return $this->get_post_count((int)$user_id);
    }

    protected function get_selected_post_status(): array
    {
        $post_status = $this->get_setting('post_status')->get_value();

        if ('' === $post_status) {
            return get_post_stati(['internal' => 0]);
        }

        return $post_status;
    }

    protected function register_settings()
    {
        $this->add_setting(new Settings\Column\PostType($this, true));
        $this->add_setting(new Settings\Column\PostStatus($this));
    }

}