<?php

namespace AC\Column\User;

use AC\Column;

class Posts extends Column
{

    public function __construct()
    {
        $this->set_original(true)
             ->set_type('posts');
    }

}