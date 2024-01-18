<?php

namespace AC\Column\Comment;

use AC\Column;

class ID extends Column
{

    public function __construct()
    {
        $this->set_type('column-comment_id')
             ->set_label(__('ID', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return $id;
    }

    public function get_raw_value($id)
    {
        return $id;
    }

}