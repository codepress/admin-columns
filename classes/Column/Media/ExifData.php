<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

class ExifData extends Column
{

    public function __construct()
    {
        $this->set_type('column-exif_data')
             ->set_group('media-image')
             ->set_label(__('Image Meta (EXIF)', 'codepress-admin-columns'));
    }

    public function get_raw_value($id)
    {
        // TODO test
        $meta = get_post_meta($id, '_wp_attachment_metadata', true) ?: [];

        return $meta['image_meta'] ?? [];
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\ExifData($this->get_label()));
    }

}