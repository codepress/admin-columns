<?php

namespace AC\Column\User;

use AC\Column;

class Email extends Column
{

    public function __construct()
    {
        $this->set_original(true);
        $this->set_type('email');
    }

}