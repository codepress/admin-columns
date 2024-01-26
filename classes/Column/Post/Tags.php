<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings\Column\Width;

class Tags extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('tags');
    }

    public function register_settings()
    {
        // TODO test
        $this->add_setting(new Width(15, '%'));
    }

}