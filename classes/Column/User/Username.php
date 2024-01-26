<?php

namespace AC\Column\User;

use AC\Column;

class Username extends Column
{

    public function __construct()
    {
        $this->set_original(true)
             ->set_type('username');
    }

}