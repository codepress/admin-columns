<?php

namespace AC\Column\Comment;

use AC\Column;

class Author extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('author');
    }

    public function register_settings()
    {
        // TODO test
        $this->get_setting('width')->with_default(20);
        // TODO how to set defaults
        //$this->get_setting('width')->set_default(20);
    }

}