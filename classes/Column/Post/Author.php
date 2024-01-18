<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings\Column\Width;

class Author extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('author');
    }

    // Todo test
    public function register_settings()
    {
        // set defaults
        $this->add_setting(new Width(10, '%'));
    }

}