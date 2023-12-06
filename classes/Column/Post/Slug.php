<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

/**
 * @since 2.0
 */
class Slug extends Column
{

    public function __construct()
    {
        $this->set_type('column-slug');
        $this->set_label(__('Slug', 'codepress-admin-columns'));
    }

    public function get_value($post_id)
    {
        $slug = $this->get_raw_value($post_id);

        if ( ! $slug) {
            return $this->get_empty_char();
        }

        return $slug;
    }

    public function get_raw_value($post_id)
    {
        return urldecode(get_post_field('post_name', $post_id, 'raw'));
    }

    public function register_settings()
    {
        $setting_limit = new Settings\Column\CharacterLimit($this);
        $setting_limit->set_default(null);

        $this->add_setting($setting_limit);
    }

}