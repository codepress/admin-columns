<?php

namespace AC\Column\Media;

use AC\Column;

class FileName extends Column
{

    public function __construct()
    {
        $this->set_type('column-file_name');
        $this->set_label(__('Filename', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return ac_helper()->html->link(
            wp_get_attachment_url($id),
            ac_helper()->image->get_file_name($id)
        );
    }

}