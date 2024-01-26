<?php

namespace AC\Column\Media;

use AC\Column;

class Title extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('title');
    }

}