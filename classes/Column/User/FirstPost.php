<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

class FirstPost extends Column
{

    public function __construct()
    {
        $this->set_type('column-first_post');
        $this->set_label(__('First Post', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $first_post_id = $this->get_raw_value($id);

        if ( ! $first_post_id) {
            return $this->get_empty_char();
        }

        $post = get_post($first_post_id);

        return $this->get_formatted_value($post->ID);
    }

    public function get_raw_value($user_id)
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

    public function get_related_post_stati(): array
    {
        // TODO test
        return (array)$this->get_option('post_status');
    }

    protected function get_related_post_type(): string
    {
        return (string)$this->get_option('post_type');
    }

    protected function register_settings()
    {
        $this->add_setting(new Settings\Column\PostType(true));
        $this->add_setting(new Settings\Column\PostStatus());
        $this->add_setting(new Settings\Column\Post());
    }

}