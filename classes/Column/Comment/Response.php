<?php

namespace AC\Column\Comment;

use AC\Column;
use AC\Settings\Column\Width;

class Response extends Column
{

    public function __construct()
    {
        $this->set_type('response');
        $this->set_original(true);
    }

    public function register_settings()
    {
        $this->add_setting(new Width(15, '%'));
    }

}