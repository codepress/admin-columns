<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings\Column\Width;

class Role extends Column
{

    public function __construct()
    {
        $this->set_type('role');
        $this->set_original(true);
    }

    public function register_settings()
    {
        // TODO implement in JS
        $this->add_setting(new Width(15, '%'));
    }

}