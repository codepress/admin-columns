<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings\Column\Width;

class Role extends Column\Meta
{

    public function __construct()
    {
        $this->set_type('role');
        $this->set_original(true);
    }

    public function get_value($id)
    {
        return null;
    }

    public function get_meta_key()
    {
        global $wpdb;

        return $wpdb->get_blog_prefix() . 'capabilities'; // WPMU compatible
    }

    public function register_settings()
    {
        $this->add_setting(new Width(15, '%'));
    }

}