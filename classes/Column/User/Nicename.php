<?php

namespace AC\Column\User;

use AC\Column;

class Nicename extends Column
{

    public function __construct()
    {
        $this->set_type('column-user_nicename')
             ->set_label(__('Author Slug', 'codepress-admin-columns'));
    }

    public function get_value($id): string
    {
        $value = get_userdata($id)->user_nicename ?? null;

        if ( ! $value) {
            return $this->get_empty_char();
        }

        $url = get_author_posts_url($id);

        if ($url) {
            $value = sprintf('<a href="%s">%s</a>', $url, $value);
        }

        return (string)$value;
    }

}