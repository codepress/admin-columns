<?php

namespace AC\Column\Comment;

use AC\Column;

class Date extends Column
{

    public function __construct()
    {
        $this->set_original(true)
             ->set_type('date');
    }

    public function register_settings()
    {
        $this->get_setting('width')->set_default(14);
    }

}