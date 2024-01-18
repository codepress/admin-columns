<?php

namespace AC\Column\Comment;

use AC\Column;
use AC\Settings\Column\Width;

class Date extends Column
{

    public function __construct()
    {
        $this->set_original(true)
             ->set_type('date');
    }

    public function register_settings()
    {
        // TODO test
        $this->add_setting(new Width(14, '%'));
    }

}