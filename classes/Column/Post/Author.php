<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * @since 3.0
 */
class Author extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('author');
    }

    // Todo does not work anymore
    public function register_settings()
    {
        //$this->get_setting( 'width' )->set_default( 10 );
    }

}