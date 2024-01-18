<?php

namespace AC\Column\User;

use AC\Column;

class Url extends Column
{

    public function __construct()
    {
        $this->set_type('column-user_url');
        $this->set_label(__('Website', 'codepress-admin-columns'));
    }

    public function get_value($user_id)
    {
        $url = $this->get_raw_value($user_id);

        if ( ! $url) {
            return $this->get_empty_char();
        }

        return sprintf('<a target="_blank" href="%1$s">%1$s</a>', $url);
    }

    public function get_raw_value($user_id)
    {
        return get_userdata($user_id)->user_url;
    }

}