<?php

namespace AC\Column\Media;

use AC\Column;

class FileSize extends Column
{

    public function __construct()
    {
        $this->set_type('column-file_size');
        $this->set_label(__('File Size', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $value = '';
        $abs = get_attached_file($id);

        if (file_exists($abs)) {
            $value = ac_helper()->file->get_readable_filesize((int)filesize($abs));
        }

        return $value;
    }

}