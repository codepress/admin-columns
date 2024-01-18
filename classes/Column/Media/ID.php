<?php

namespace AC\Column\Media;

use AC\Column;

class ID extends Column
{

    public function __construct()
    {
        $this->set_type('column-mediaid');
        $this->set_label(__('ID', 'codepress-admin-columns'));
    }

    public function get_raw_value($id)
    {
        return $id;
    }

}