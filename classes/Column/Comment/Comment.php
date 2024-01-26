<?php

namespace AC\Column\Comment;

use AC\Column;

class Comment extends Column
{

    public function __construct()
    {
        $this->set_original(true)
             ->set_type('comment');
    }

}