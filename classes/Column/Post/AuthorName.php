<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 */
class AuthorName extends Column
{

    public function __construct()
    {
        $this->set_type('column-author_name');
        $this->set_label(__('Author', 'codepress-admin-columns'));
    }

    public function get_raw_value($post_id)
    {
        return $this->get_post_author($post_id);
    }

    private function get_post_author($post_id)
    {
        return ac_helper()->post->get_raw_field('post_author', (int)$post_id);
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\User());
    }

}