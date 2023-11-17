<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

class ExifData extends Column\Media\MetaValue
{

    public function __construct()
    {
        $this->set_type('column-exif_data')
             ->set_group('media-image')
             ->set_label(__('Image Meta (EXIF)', 'codepress-admin-columns'));
    }

    protected function get_option_name()
    {
        return 'image_meta';
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\ExifData($this));
    }

}