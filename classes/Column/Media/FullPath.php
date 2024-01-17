<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

class FullPath extends Column
{

    public function __construct()
    {
        $this->set_type('column-full_path');
        $this->set_label(__('Path', 'codepress-admin-columns'));
    }

    public function get_raw_value($id)
    {
        return wp_get_attachment_url($id);
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\PathScope());
    }

}