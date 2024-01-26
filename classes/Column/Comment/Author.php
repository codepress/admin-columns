<?php

namespace AC\Column\Comment;

use AC\Column;
use AC\Settings\Column\Width;

class Author extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('author');
    }

    public function register_settings()
    {
        $this->add_setting(new Width(20, '%'));
    }

}