<?php

namespace AC\Column\Post;

use AC\Column;

class Tags extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('tags');
    }

    public function register_settings()
    {
        //TODO David, replace width setting with new default
        //$this->get_setting( 'width' )->set_default( 15 );
    }

    // TODO remove
    public function get_taxonomy()
    {
        return 'post_tag';
    }

}