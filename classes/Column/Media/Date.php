<?php

namespace AC\Column\Media;

use AC\Column;

class Date extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('date');
    }

    public function register_settings()
    {
        $this->get_setting('width')->set_default(10);
    }

}