<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

abstract class FileMeta extends Column\Meta
{

    public function get_meta_key()
    {
        return '_wp_attachment_metadata';
    }

    /**
     * @return Settings\Column\FileMeta
     */
    protected function get_media_setting()
    {
        $setting = $this->get_setting('media_meta');

        return $setting instanceof Settings\Column\FileMeta
            ? $setting
            : null;
    }

    public function get_sub_keys(): array
    {
        return array_filter(array_map('trim', explode('.', $this->get_option('media_meta_key'))));
    }

    protected function get_metadata_value(array $data, array $keys)
    {
        $data = ac_helper()->array->get_nested_value($data, $keys);

        return is_scalar($data)
            ? $data
            : null;
    }

    public function get_raw_value($id)
    {
        $data = $this->get_meta_value($id, $this->get_meta_key());

        return is_array($data)
            ? $this->get_metadata_value($data, $this->get_sub_keys())
            : null;
    }

}