<?php

declare(strict_types=1);

namespace AC\Column\Media;

use AC\Column;

abstract class FileMeta extends Column\Meta
{

    public function get_meta_key(): string
    {
        return '_wp_attachment_metadata';
    }

    public function get_sub_keys(): array
    {
        return array_filter(array_map('trim', explode('.', $this->get_option('media_meta_key'))));
    }

    protected function get_metadata_value(array $data, array $keys)
    {
        $value = ac_helper()->array->get_nested_value($data, $keys);

        return is_scalar($value)
            ? $value
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