<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

/**
 * @since 2.0
 */
class ID extends Column
{

    public function __construct()
    {
        $this->set_type('column-postid');
        $this->set_label(__('ID', 'codepress-admin-columns'));
    }

    public function get_raw_value($post_id)
    {
        return $post_id;
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\BeforeAfter());
    }

}