<?php

namespace AC\Column\User;

use AC\Column;

class DisplayName extends Column
{

    public function __construct()
    {
        $this->type = 'column-display_name';
        $this->label = __('Display Name', 'codepress-admin-columns');
    }

    public function get_value($id): string
    {
        return get_userdata($id)->display_name ?? $this->get_empty_char();
    }

}