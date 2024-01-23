<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

class FeaturedImage extends Column
{

    public function __construct()
    {
        $this->set_type('column-featured_image')
             ->set_label(__('Featured Image', 'codepress-admin-columns'));
    }

    public function get_value($id): string
    {
        // TODO test
        return parent::get_value($id) ?: $this->get_empty_char();
    }

    public function get_raw_value($id): ?int
    {
        if ( ! has_post_thumbnail($id)) {
            return null;
        }

        return get_post_thumbnail_id($id);
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\Image());
    }

}