<?php

namespace AC\Column\User;

use AC\Column;

class LastName extends Column
{

    public function __construct()
    {
        $this->set_type('column-last_name');
        $this->set_label(__('Last Name', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return get_user_meta($id, 'last_name', true) ?: $this->get_empty_char();
    }

}