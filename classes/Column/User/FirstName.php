<?php

namespace AC\Column\User;

use AC\Column;

class FirstName extends Column
{

    public function __construct()
    {
        $this->set_type('column-first_name')
             ->set_label(__('First Name', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return get_user_meta($id, 'first_name', true) ?: $this->get_empty_char();
    }

}