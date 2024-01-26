<?php

namespace AC\Column\Media;

use AC\Column;

class Width extends Column
{

    public function __construct()
    {
        $this->set_type('column-width')
             ->set_group('media-image')
             ->set_label(__('Width', 'codepress-admin-columns'));
    }

    public function get_value($id): string
    {
        $meta = get_post_meta($id, '_wp_attachment_metadata', true);

        $value = $meta['width'] ?? null;

        return $value
            ? $value . 'px'
            : $this->get_empty_char();
    }

}