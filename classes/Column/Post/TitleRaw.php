<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

/**
 * @since 2.2.4
 */
class TitleRaw extends Column
{

    public function __construct()
    {
        $this->set_type('column-title_raw');
        $this->set_label(__('Title Only', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        return sprintf('<span class="row-title">%s</span>', wp_kses_post(parent::get_value($id)));
    }

    function get_raw_value($post_id)
    {
        return get_post_field('post_title', $post_id);
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\PostLink($this));
    }

}