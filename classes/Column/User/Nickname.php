<?php

namespace AC\Column\User;

use AC\Column;

class Nickname extends Column
{

    public function __construct()
    {
        $this->set_type('column-nickname');
        $this->set_label(__('Nickname', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return get_user_meta($id, 'nickname');
    }

}