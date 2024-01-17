<?php

namespace AC\Column\Comment;

use AC\Column;

class Response extends Column
{

    public function __construct()
    {
        $this->set_type('response');
        $this->set_original(true);
    }

    public function register_settings()
    {
        // TODO
        //$this->get_setting( 'width' )->set_default( 15 );
    }

}